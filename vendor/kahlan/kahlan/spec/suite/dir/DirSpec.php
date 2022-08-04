<?php
namespace dir\spec\suite;

use kahlan\dir\Dir;
use Exception;

describe("Dir", function() {

    $this->normalize = function($path) {
        if (!is_array($path)) {
            return str_replace('/', DIRECTORY_SEPARATOR, $path);
        }
        $result = [];
        foreach ($path as $p) {
            $result[] = $this->normalize($p);
        }
        return $result;
    };

    describe("::scan()", function() {

        $sort = function($files) {
            sort($files);
            return $files;
        };

        beforeEach(function() {
            $this->path = 'spec/fixture/dir';
        });

        it("scans files", function() {

            $files = Dir::scan($this->path, [
                'type' => 'file',
                'recursive' => false
            ]);
            expect($files)->toBe($this->normalize(['spec/fixture/dir/file1.txt']));

        });

        it("scans and show dots", function() use ($sort) {

            $files = Dir::scan($this->path, [
                'skipDots' => false,
                'recursive' => false
            ]);

            expect($sort($files))->toBe($sort($this->normalize([
                'spec/fixture/dir/.',
                'spec/fixture/dir/..',
                'spec/fixture/dir/file1.txt',
                'spec/fixture/dir/nested',
                'spec/fixture/dir/extensions'
            ])));

        });

        it("scans and follow symlinks", function() use ($sort) {

            $files = Dir::scan($this->path . DIRECTORY_SEPARATOR . 'extensions', [
                'followSymlinks' => false,
                'recursive' => false
            ]);

            expect($sort($files))->toBe($this->normalize([
                'spec/fixture/dir/extensions/childs',
                'spec/fixture/dir/extensions/file.xml',
                'spec/fixture/dir/extensions/index.html',
                'spec/fixture/dir/extensions/index.php'
            ]));

        });

        it("scans files recursively", function() use ($sort) {

            $files = Dir::scan($this->path . DIRECTORY_SEPARATOR . 'nested', [
                'type' => 'file'
            ]);

            expect($sort($files))->toBe($this->normalize([
                'spec/fixture/dir/nested/childs/child1.txt',
                'spec/fixture/dir/nested/nested_file1.txt',
                'spec/fixture/dir/nested/nested_file2.txt'
            ]));

        });

        it("scans files & directores recursively", function() use ($sort) {

            $files = Dir::scan($this->path . DIRECTORY_SEPARATOR . 'nested');

            expect($sort($files))->toBe($this->normalize([
                'spec/fixture/dir/nested/childs',
                'spec/fixture/dir/nested/childs/child1.txt',
                'spec/fixture/dir/nested/nested_file1.txt',
                'spec/fixture/dir/nested/nested_file2.txt'
            ]));

        });

        it("scans only leaves recursively", function() use ($sort) {

            $files = Dir::scan($this->path. DIRECTORY_SEPARATOR . 'nested', [
                'leavesOnly' => true
            ]);

            expect($sort($files))->toBe($this->normalize([
                'spec/fixture/dir/nested/childs/child1.txt',
                'spec/fixture/dir/nested/nested_file1.txt',
                'spec/fixture/dir/nested/nested_file2.txt'
            ]));

        });

        it("scans txt files recursively", function() use ($sort) {

            $files = Dir::scan($this->path, [
                'include' => '*.txt',
                'type' => 'file'
            ]);

            expect($sort($files))->toBe($this->normalize([
                'spec/fixture/dir/extensions/childs/child1.txt',
                'spec/fixture/dir/file1.txt',
                'spec/fixture/dir/nested/childs/child1.txt',
                'spec/fixture/dir/nested/nested_file1.txt',
                'spec/fixture/dir/nested/nested_file2.txt'
            ]));

        });

        it("scans non nested txt files recursively", function() use ($sort) {

            $files = Dir::scan($this->path, [
                'include' => '*.txt',
                'exclude' => '*nested*',
                'type' => 'file'
            ]);

            expect($sort($files))->toBe($this->normalize([
                'spec/fixture/dir/extensions/childs/child1.txt',
                'spec/fixture/dir/file1.txt'
            ]));

        });

        it("throws an exception if the path is invalid", function() {

            $closure = function() {
                Dir::scan('non/existing/path', [
                    'type' => 'file',
                    'recursive' => false
                ]);
            };
            expect($closure)->toThrow(new Exception());

        });

        it("returns itself when the path is a file", function() {

            $files = Dir::scan('spec/fixture/dir/file1.txt', [
                'include' => '*.txt',
                'exclude' => '*nested*',
                'type' => 'file'
            ]);
            expect($files)->toBe($this->normalize(['spec/fixture/dir/file1.txt']));

        });

    });

    describe("::copy()", function() {

        beforeEach(function() {
            $this->tmpDir = Dir::tempnam(sys_get_temp_dir(), 'spec');
        });

        afterEach(function() {
            Dir::remove($this->tmpDir, ['recursive' => true]);
        });

        it("copies a directory recursively", function() {

            Dir::copy('spec/fixture/dir', $this->tmpDir);

            $paths = Dir::scan('spec/fixture/dir');

            foreach ($paths as $path) {
                $target = preg_replace('~^spec/fixture~', '', $path);
                expect(file_exists($this->tmpDir . $target))->toBe(true);
            }

        });

        it("copies a directory recursively but not following symlinks", function() {

            Dir::copy('spec/fixture/dir', $this->tmpDir, ['followSymlinks' => false]);

            $paths = Dir::scan('spec/fixture/dir');

            foreach ($paths as $path) {
                $target = preg_replace('~^spec/fixture~', '', $path);
                if ($target === $this->normalize('/dir/extensions/childs/child1.txt')) {
                    expect(file_exists($this->tmpDir . $target))->toBe(false);
                } else {
                    expect(file_exists($this->tmpDir . $target))->toBe(true);
                }
            }

        });

        it("throws an exception if the destination directory doesn't exists", function() {

            $closure = function() {
                Dir::copy('spec/fixture/dir', 'unexisting/folder');
            };

            expect($closure)->toThrow(new Exception("Unexisting destination path `unexisting/folder`."));

        });

    });

    describe("::remove()", function() {

        it("removes a directory recursively", function() {

            $this->tmpDir = Dir::tempnam(sys_get_temp_dir(), 'spec');

            Dir::copy('spec/fixture/dir', $this->tmpDir);

            $paths = Dir::scan('spec/fixture/dir');

            Dir::remove($this->tmpDir);

            foreach ($paths as $path) {
                $target = preg_replace('~^spec~', '', $path);
                expect(file_exists($this->tmpDir . $target))->toBe(false);
            }

            expect(file_exists($this->tmpDir))->toBe(false);

        });

    });

    describe("::make()", function() {

        beforeEach(function() {
            $this->umask = umask(0);
            $this->tmpDir = Dir::tempnam(sys_get_temp_dir(), 'spec');
        });

        afterEach(function() {
            Dir::remove($this->tmpDir, ['recursive' => true]);
            umask($this->umask);
        });

        it("creates a nested directory", function() {

            $path = $this->tmpDir . '/my/nested/directory';
            $actual = Dir::make($path);
            expect($actual)->toBe(true);

            expect(file_exists($path))->toBe(true);

            $stat = stat($path);
            $mode = $stat['mode'] & 0777;
            expect($mode)->toBe(0755);

        });

        it("creates a nested directory with a specific mode", function() {

            $path = $this->tmpDir . '/my/nested/directory';
            $actual = Dir::make($path, ['mode' => 0777]);
            expect($actual)->toBe(true);

            expect(file_exists($path))->toBe(true);

            $stat = stat($path);
            $mode = $stat['mode'] & 0777;
            expect($mode)->toBe(0777);

        });

        it("creates multiple nested directories in a single call", function() {

            $paths = [
                $this->tmpDir . '/my/nested/directory',
                $this->tmpDir . '/sub/nested/directory'
            ];
            $actual = Dir::make($paths);
            expect($actual)->toBe(true);

            foreach ($paths as $path) {
                expect(file_exists($path))->toBe(true);
            }

        });

    });

    describe("::tempnam()", function() {

        it("uses the system temp directory by default", function() {

            $dir = Dir::tempnam(null, 'spec');

            $temp = sys_get_temp_dir();

            expect($dir)->toMatch('~^' . $temp . '/spec~');

            Dir::remove($dir);

        });

    });

});
