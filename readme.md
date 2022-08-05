# Talently Challenge 

## Configuración

Este repositorio incluye la configuración inicial para este problema, incluyendo los specs. Usa la librería de [Kahlan](http://kahlan.readthedocs.org/en/latest/), que probablemente no has usado. Pero no te preocupes, no hay mucho que aprender. Revisa los specs y entenderás la sintaxis básica en menos de un minuto.

Tu tarea es:

1. Refactorizar el código en la clase `VillaPeruana.php`.
2. Agregar un nuevo typo de elemento, "Café". Las especificaciones para este elemento están comentadas en el archivo `VillaPeruanaScpec.php`.

## Flujo

Debes tener instalado docker en tu computadora para usar nuestros comandos del flujo de trabajo

- Usa el comando `./start` para inicializar el docker
- Usa el comando `./test` para correr los tests
- Usa el comando `./finish` para desactivar el docker

# Reglas

Hola y bienvenido al equipo La Villa Peruana. Como sabes, somos una pequeña posada, con una excelente ubicación en una ciudad importante, administrada por nuestra amigable Allison. También compramos y vendemos los más finos productos. Desafortunadamente, nuestros productos se van desgradando constantemente en calidad conforme se acercan a su fecha de vencimiento. Tenemos un sistema que actualiza nuestro inventario por nosotros. Fue desarrollado por un desarrollador llamado Elmo, quien ha ido en busca de nuevas aventuras.

Queremos agregar una nueva categoría de productos al sistema y para ello necesitamos tu ayuda.

Primero, una introducción a nuestro sistema:

- Todos los productos tienen un SellIn que denota el número de días que se tienen para vender el producto
- Todos los productos tienen un Quality que denota cuán valioso es el producto
- Al final de cada día, nuestro sistema disminuye los ambos valores para cada producto

Bastante simple, ¿verdad? Bueno, acá se pone interesante:

- Cuando la fecha de venta ha pasado, el Quality se degrada dos veces más rápido
- El Quality de un producto nunca es negativo
- Los productos "Pisco Peruano", en realidad, incrementan en Quality mientras más viejos están
- El Quality de un producto nunca es mayor a 50
- Los productos "Tumi", siendo un producto legendario, nunca debe ser vendido o bajaría su Quality
- Los "Tickets VIP", así como "Pisco Peruano", incrementan su Quality conforme su SellIn se acerca a 0, el Quality incrementa en 2 cuando faltan 10 días o menos y en 3 cuando faltan 5 días o menos, pero el Quality disminuye a 0 después del concierto.

Recientemente hemos firmado un contrato con un proveedor de productos de "Café". Esto require una actualización para nuestro sistema:

- Los productos de "Café" se degradan en Quality el doble que los productos normales

Para dejarlo claro, un producto nunca puede incrementar su Quality mayor a 50, sin embargo "Tumi" es un producto legendario y como tal su Quality es 80 y nunca cambia.

# Entregable o Expectativa del reto

- La limpieza y legibilidad del código será considerada.
- La eficiencia del código en cuestiones de rendimiento sumarán para esta prueba.
- Será indispensable uso de principios S.O.L.I.D.
- Al finalizar el reto, enviar el enlace de la solución a emmanuel.barturen@talently.tech con copia a cristian@talently.tech con título "Reto Talently Backend"

# Preguntas de conocimiento en Laravel

1. Qué paquete o estrategia utilizarías para levantar un sistema de administración rápidamente? (Autenticación y CRUDs)
    * R: Existe varias dependencias, unas que pueden ser instalada conjuntamente con un nuevo proyecto
         de laravel, otras se instalan como dependecias adicionales. Para mi la mejor opción es utilizar 
         Laravel Breeze con Blade, ya que viene por defecto. Ahora si se desea usar un Legacy de Bootstrap,
         se puede utilizar la dependencia de Laravel/ui con el stack de bootstrap.

2. Una breve explicación de cómo laravel utiliza la injección de dependencias
    * R: La inyección de dependencias es el encargado de instanciar las clases que necesitemos
         y suministrarnos ("inyectar") las dependencias enviando los parámetros oportunos al constructor.
         Cumple con uno de los pricipios S.O.L.I.D, el cual hace referencia a la Inversión de Dependencias 
         (DI) y evita la dependencia rígida entre componentes.

3. En qué casos utilizarías un Query Scope?
    * R: La utilización de un Query Scope dependerá del alcance que se desea en la consulta, ya que 
         Laravel puede implementar 2 tipos de Query Scope, local y global. 
         Si utilizamos un Query Scope Global, permitiría que una consulta pueda ser aplicada cualquier
         modelo que se le necesite. En cambio el Query Scope Local, se crea como método dentro del model que lo requiere.

4. Qué convenciones utilizas en la creación e implementación de migraciones?
    * R: De acuerdo con las convenciones PSR para programación en PHP, laravel utiliza por
         defecto dichas convenciones. Por ejemplo al utilizar el comando: php artisan make:migration 
         create_nombreTablaPlural_table, éste creará el archivo en la carpeta database/migrations, con la 
         estructura <timestamps_create_nombreTablaPlural_table>. Ahora si se desea crear a partir de un 
         modelo, usando el comando: php artisan make:model nombreModeloSingular -m, como opción para que 
         automáticamente genere la migración correspondiente a dicho modelo.
         
