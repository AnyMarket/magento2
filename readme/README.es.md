Módulo de integración ANYMARKET y Magento 2.x.x
===========================================
---
Versión actual del módulo: **3.3.0**
---

**Versiones Magento 2**
========================
> - Este módulo es compatible con **Magento 2** y fue aprovado por nuestro equipo hasta la versión **2.3.5**;
>
> **IMPORTANTE**
> - Antes de hacer cualquier actualización de la versión del Magento, deve ser comunicado con al equipo de soporte (**suporte@anymarket.com.br**) para 
acompañar y garantizar un flujo de operación de la integración;
>
> Sin seguir estas pautas, su integración **puede tener error**.
> **No se perderán los datos de la versión anterior.**

Descripción
---------
¡Hola! Con el módulo de integración [ANYMARKET] cargado y configurado és posible que la integración automática de:
- Productos
- Ventas
- Inventario

Instalación
----------
**Quedarse atento a los consejos que van a ayudarte a tener éxito en su integración**

- Haga un Backup de Magento completo;
- Asegurarse que no hay otro módulo Anymarket cargado en su sistema;
- Descargue el repositorio como un archivo zip o hacer un fork del proyecto;
- Descarga en su servidor, a través de composer;
- Las otras configuraciones están disponibles en nuestro material de [entreinamiento];

Requerimientos mínimos
------------------
- [Magento] 2.x.x (hasta la `2.3.5`)
- El servidor debe tener una capacidad de procesamiento que no se vea afectada entre otras integraciones y el sitio web, nuestra integración funciona con 
  las siguientes configuraciones:
    - Cuantidad de requisiciones por segundo (predeterminado): 15 llamadas por segundo;
    - Time out estándar de 20.000 ms, después eso se cancela la operación, que puede causar erros de actualización entre las plataformas;

Programadores
----
- Si necesita información de la API [ANYMARKET], accede en: http://developers.anymarket.com.br/;
- Este módulo fue desarrollado segundo la especificación en la documentación [Magento 2], entonces no hacemos personalizaciones en API que no están 
  disponibles en la documentación Magento;

Licencia
-------
Este código fuente tiene licencia según los términos de la **Licencia pública de Mozilla, versión 2.0**.
Si no puede encontrar una copia distribuida con los archivos, puede obtener una en: https://mozilla.org/MPL/2.0/.

Más información o asociación
--------
Si tiene alguna pregunta, estamos disponibles para ayudarlo en lo que necesite: http://www.anymarket.com.br/ o en nuestro [blog].

Contribuciones
-------------
Si ha encontrado o arreglar un BUG, o tiene una feature en mente y desear compartir con nuestro equipo [ANYMARKET], nos quedamos muy felices y te sugerimos 
los pasos a seguir:
* Hacer un fork.
* Agrega a su función o corrección de bug.
* Envíe un pull request en [GitHub].

Magento Service 1.x.x
-------------
Si tu Magento es la versión 1.x.x, hacer la instalación del módulo disponible en [ANYMARKET Magento1].


[Magento]: https://www.magentocommerce.com/
[Magento 2]: https://devdocs.magento.com/
[entreinamiento]: https://treinamento.anymarket.com.br/
[ANYMARKET]: http://www.anymarket.com.br
[GitHub]: https://github.com/AnyMarket/magento2
[blog]: http://marketplace.anymarket.com.br/
[ANYMARKET Magento1]: https://github.com/AnyMarket/magentoService
