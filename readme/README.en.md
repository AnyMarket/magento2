ANYMARKET and Magento 2.x.x - Integration Module
===========================================
---
Current module version: **3.4.0**
---

**Magento 2 - versions**
========================
| Version |             Status              | Situation                                                                                                                                                                         |
|:-------:|:-------------------------------:|-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
|  2.3.5  |            Approved             | Approved, backoffice flows and sales channel released 100% for integration;                                                                                                       |
|  2.4.3  | Approved <br/>(1 problem found) | Approved, backoffice flow released for integration, sales channel flow released with the following caveat:<br/> - Price scope configuration that needs to be done by Store View;  |
|  2.4.5  | Approved <br/>(1 problem found) | Approved, backoffice flow released for integration, sales channel flow released with the following caveats:<br/> - Price scope configuration that needs to be done by Store View; |

> **WARNING**
> - Our support team (**suporte@anymarket.com.br**) must be contacted before performing any Magento version update, that way we'll be able to follow up your 
update and to guarantee the proper integration operation; 
>
> Your integration might **not work properly** in case these instructions are not followed.
> **No previous version data will be lost.**

Description
---------
Hi! With ANYMARKET integration module properly installed and set up, the following automatic integrations will be possible:
- Products
- Orders
- Stock (multiple stocks service)

Installation
----------
**Stay tuned on the tips that will help you succeed in your installation**

- Perform a full Magento Backup;
- Guarantee that there aren't other [ANYMARKET] modules installed on your system;
- Download the repository zip file or create a project fork;
- Perform the installation on your server, using composer;
- Other settings are available on our [training] material;

Minimum requirements
------------------
- [Magento] 2.x.x (as described in the versions in the table above)
- The server must have a processing capacity in a way that it won't be affected by the website and other integrations. Our integration works with the 
following settings:
  - Amount of request per second (default): 15 requests per second;
  - The Default timeout is 20.000 ms, the operation is cancelled beyond that, which might result on updates errors between the platforms;

Developers
----
- If you need information about the [ANYMARKET] API, you can go to http://developers.anymarket.com.br/;
- This module was developed following the specified [Magento 2] documentation, therefore, we do not perform API customizations that are not available on 
  Magento's documentation; 

License
-------
This Source Code Form is subject to the terms of the **Mozilla Public License, v. 2.0**. 
If a copy of the MPL was not distributed with this file, you can obtain one at https://mozilla.org/MPL/2.0/.

More info or partnerships
--------
If you have any doubts, we are happy to help you with them on: http://www.anymarket.com.br/ or on our [Blog].

Contributions
-------------
If you found or fixed a BUG, or have any features in mind that you want to share with our team,[ANYMARKET] we'll be grateful, and we suggest the following steps:
* Create a fork.
* Add your feature or BUG fix.
* Perform a pull request on [GitHub].

Magento Service 1.x.x
-------------
If your Magento version is 1.x.x you should perform the module installation available on [ANYMARKET Magento1].

[Magento]: https://www.magentocommerce.com/
[Magento 2]: https://devdocs.magento.com/
[training]: https://treinamento.anymarket.com.br/
[ANYMARKET]: http://www.anymarket.com.br
[GitHub]: https://github.com/AnyMarket/magento2
[blog]: http://marketplace.anymarket.com.br/
[ANYMARKET Magento1]: https://github.com/AnyMarket/magentoService
