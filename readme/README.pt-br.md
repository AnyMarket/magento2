Módulo de integração ANYMARKET e Magento 2.x.x
===========================================
---
Versão atual do módulo: **3.4.0**
---

**Versões Magento 2**
========================

| Versão |                Situação                 | Status                                                                                                                                                                                                  |
|:------:|:---------------------------------------:|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| 2.3.5  |               Homologado                | Homologado, fluxos de backoffice e canal de venda liberados 100% para integração;                                                                                                                       |
| 2.4.3  | Homologado <br/>(1 problema encontrado) | Homologado, fluxo de backoffice liberado para integração, fluxo canal de venda liberado com a seguinte ressalva:<br/> - Configuração de escopo de preço que necessita ser feito por Store View;         |
| 2.4.5  | Homologado <br/>(1 problema encontrado) | Homologado, fluxo de backoffice liberado para integração, fluxo canal de venda liberado com as seguintes ressalvas:<br/> - Configuração de escopo de preço que necessita ser feito por Store View;<br/> |

> **IMPORTANTE**
> - Antes de realizar qualquer atualização de versão do Magento, deverá ser comunicado o time de suporte (**suporte@anymarket.com.br**) para que seja feito o
    acompanhamento e possamos garantir o devido funcionamento da integração;
>
> Sem seguir essas orientações, sua integração **pode não funcionar corretamente**.
> **Nenhum dado será perdido da versão anterior.**

Descrição
---------
Olá! Com o módulo de integração [ANYMARKET] instalado e configurado será possível a integração automática de:
- Produtos
- Pedidos
- Estoque (contempla múltiplos estoques)

Instalação
----------
**Fique ligado nas dicas que vão ajudar ter sucesso na sua instalação**

- Realize um Backup do Magento completo;
- Certifique-se que não há outros módulos [ANYMARKET] instalados no seu sistema;
- Baixe o repositório como arquivo zip ou faça um fork do projeto;
- Faça a instalação em seu servidor, via composer;
- As demais configurações estão disponíveis no nosso material de [treinamento];

Requisitos mínimos
------------------
- [Magento] 2.x.x (conforme descritivo das versões na tabela acima)
- Servidor deve possuir a capacidade de processamento que não seja impactada entre outras integrações e site, nossa integração trabalha com as seguintes
  configurações:
    - Quantidade de requisições por segundo (default): 15 chamadas por segundo;
    - Time out padrão de 20.000 ms, após isso a operação é cancelada, o que pode gerar falhas de atualização entre as plataformas;

Desenvolvedores
----
- Caso precise de informações sobre a API [ANYMARKET] acesse em: http://developers.anymarket.com.br/;
- Este módulo foi desenvolvido segundo especificado na documentação [Magento 2], portanto não fazemos personalizações na API que não estejam disponíveis na
  documentação Magento;

Licença
-------
Este código-fonte está licenciado sob os termos da **Mozilla Public License, versão 2.0**.
Caso não encontre uma cópia distribuída com os arquivos, pode obter uma em: https://mozilla.org/MPL/2.0/.

Mais informações ou parcerias
--------
Caso tenha dúvidas, estamos à disposição para atendê-lo no que for preciso: http://www.anymarket.com.br/ ou em nosso [blog].

Contribuições
-------------
Caso tenha encontrado ou corrigido um bug, ou tem alguma feature em mente e queira dividir com a equipe [ANYMARKET] ficamos muito gratos e sugerimos os 
passos a seguir:

* Faça um fork.
* Adicione a sua feature ou correção de bug.
* Envie um pull request no [GitHub].

Magento Service 1.x.x
-------------
Caso o seu Magento esteja na versão 1.x.x realizar a instalação do módulo disponível em [ANYMARKET Magento1].


[Magento]: https://www.magentocommerce.com/
[Magento 2]: https://devdocs.magento.com/
[treinamento]: https://treinamento.anymarket.com.br/
[ANYMARKET]: http://www.anymarket.com.br
[GitHub]: https://github.com/AnyMarket/magento2
[blog]: http://marketplace.anymarket.com.br/
[ANYMARKET Magento1]: https://github.com/AnyMarket/magentoService
