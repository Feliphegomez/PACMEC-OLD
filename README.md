# PACMEC
## La solucion más completa del mercado

Conozca los diferentes sistemas integrados que conforman su estrategia digital y cómo pueden aportar valor desde el punto de vista del comercio electrónico.

#### ¿QUÉ SIGNIFICA TODO ESTO?

- **PIM**: Gestión de información de productos
- **AMS**: Sistema de gestión de asociaciones
- **CRM**: Gestión de relaciones con el cliente
- **MAP**: Plataforma de automatización de marketing
- **ERP**: planificación de recursos empresariales
- **CMS**: sistema de gestión de contenido

## PIM: Gestión de información de productos

Si bien la solución ERP de una empresa puede contener datos esenciales para administrar las operaciones de su empresa, es posible que no tenga información detallada sobre sus productos. Ahí es donde entra la Gestión de información de productos, que proporciona una solución para almacenar información de productos enriquecida, incluidos activos digitales como imágenes y descripciones de productos y medios que los clientes verán. El almacenamiento de estos datos en un repositorio central permite que su sitio web acceda a ellos o que se utilicen en otros materiales de marketing, como catálogos impresos. PIM proporciona una solución de middleware que toma los datos de su ERP, los combina con los medios, la información descriptiva y la copia de marketing que ha creado, y los coloca frente a sus clientes.integrar su sitio web con sus operaciones diarias y garantizar la coherencia en todas las comunicaciones relacionadas con el producto que ven sus clientes. En este caso de uso, gran parte de la información detallada del producto, como descripciones web, imágenes, etc., se almacenaría y editaría dentro del PIM y se enviaría a las herramientas de CMS / comercio electrónico.

## AMS: Sistema de gestión de asociaciones

Las asociaciones gestionan sus datos de forma diferente a otras empresas empresariales, por lo que necesitan una solución diferente. Un sistema de gestión de asociaciones cumple la función de ERP para la mayoría de las asociaciones, gestionando membresías, renovaciones e información de productos para cualquier material que venda la asociación. En este caso de uso, la ruta de integración del sitio web de AMS es similar a la ruta de integración de ERP descrita más adelante.

## CRM: Gestión de relaciones con el cliente

Muchas empresas utilizan soluciones de gestión de relaciones con los clientes para gestionar las interacciones con sus clientes, incluido el mantenimiento de la información de contacto, el seguimiento de las ventas y la prestación de asistencia al cliente. Las herramientas de CRM pueden ayudarlo a realizar un seguimiento de todos los datos de sus clientes y mantener la comunicación necesaria que necesita para brindar el mejor servicio a sus clientes. Muchas veces, si una corporación no está utilizando actualmente un MAP para sus formularios web to lead, la integración directa con un CRM suele ser la mejor solución cuando se intenta automatizar los leads del sitio web en sus flujos de trabajo de back-end.

En este caso de uso, en lugar de simplemente enviar un correo electrónico cuando un visitante del sitio web envía un formulario desde el sitio web, puede enviar esos datos directamente a su CRM, donde puede comenzar a moverse de inmediato a través de sus flujos de trabajo, ahorrando tiempo y aumentando la satisfacción del cliente.

## MAP: Plataforma de automatización de marketing

Una vez que su sitio web impulsado por CMS esté en funcionamiento, probablemente realizará un seguimiento de los análisis para medir el rendimiento de su sitio web desde la perspectiva del tráfico web. Es posible que también esté haciendo un seguimiento de conversiones en el lado del comercio electrónico. Con el tiempo, comprender datos como la cantidad de visitantes, el tiempo que pasan en el sitio, etc., solo te llevará hasta cierto punto. Los requisitos de su negocio dictarán la necesidad de comprender quiénes son los visitantes del sitio web (por ejemplo, "Chris Osterhout de Diagram"). Una vez que sepa quiénes son los visitantes del sitio web, querrá vincular toda esta gran información cualitativa con sus herramientas de marketing y ventas. Aquí es donde entra en juego una plataforma de automatización de marketing (MAP). En el back-end, una plataforma de marketing juega un papel esencial para obtener información procesable sobre lo que hacen sus visitantes en su sitio web. En este caso de uso, el MAP se integra tanto con el CMS como con el comercio electrónico para completar un ciclo de vida completo del cliente.

## ERP: planificación de recursos empresariales

Muchas empresas empresariales utilizan soluciones de recursos para administrar sus operaciones diarias, manejando datos como registros de clientes, precios de productos e inventario. Lo que no siempre está claro es cómo estos datos se pueden integrar sin problemas en su sitio web. En muchos casos, los sitios web de comercio electrónico crean y cumplen pedidos web a través de las propias herramientas de comercio electrónico. Sin embargo, esto generalmente requiere una entrada manual en un sistema de back-end, como un ERP, para cumplir con los pedidos y enviarlos al cliente final. Aquí es donde una integración perfecta entre su sitio web de comercio electrónico y la capa de interfaz de programación de aplicaciones (API) de su ERP ahorra tiempo y dinero. En este caso de uso, el pedido web podría crearse en el sitio web de comercio electrónico, pero en lugar de completar el pedido dentro de la propia herramienta CMS / comercio electrónico, puede utilizar un flujo de trabajo para enviar el pedido directamente al ERP back-end de su empresa. 

## CMS: sistema de gestión de contenido

La mayoría de las empresas utilizan un sistema de gestión de contenido para ejecutar su sitio web, ya que un CMS proporciona las herramientas para administrar su contenido de forma dinámica e incluye capacidades como comercio electrónico, personalización, contenido dirigido, flujo de trabajo y más. Si desea poder editar su sitio web y productos de comercio electrónico a través de un centro de administración y utilizar las funciones antes mencionadas para ayudar a llegar a sus clientes, un sitio web impulsado por CMS es esencial. En este caso de uso, el CMS controlaría y almacenaría tanto el contenido como los activos del sitio web, además de toda la información del producto de comercio electrónico.

#### Proudly Developed by [FelipheGomez]

> Si te gustó este desarrollo no dudes en 
> apoyarme para sacar nuevos productos
> Gracias por tu aporte
> Si no puedes aportar no te preocupes, disfrutalo y deja tus comentarios.

[FelipheGomez]: <https://github.com/FelipheGomez>

## Requisitos

Recuerda tener en cuenta los requisitos del alojamiento/servidor y la base de datos-

- PHP 7.0 o superior con controladores PDO habilitados para uno de estos sistemas de base de datos:
- MySQL 5.6 / MariaDB 10.0 o superior para características espaciales en MySQL
- PostgreSQL 9.1 o superior con PostGIS 2.0 o superior para características espaciales
- SQL Server 2012 o superior (2017 para compatibilidad con Linux)
- SQLite 3.16 o superior (las características espaciales NO son compatibles)

## Instalacion

- Descarga `PACMEC` y extrae el contenido en tu carpeta final. 
    *Nota*: Si estas utilizando una consola SSH realiza lo siguiente:
    ```sh
    cd public_path
    git clone https://github.com/Feliphegomez/PACMEC.git
    ```
- Ingresa con tu navegador preferido a: `https://misitio.com`
- Sigue los pasos del asistente.
