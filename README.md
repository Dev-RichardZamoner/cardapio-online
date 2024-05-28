# Resumo do Projeto
- **Ainda em Desenvolvimento**

## Funcionalidades Implementadas

- **Sistema de Login**: Implementação de um sistema de login que verifica se o usuário está logado e obtém informações como e-mail e rank do usuário.

- **Publicação de Notícias**: Criação de uma funcionalidade que permite aos usuários logados publicar notícias. As notícias são adicionadas ao banco de dados e associadas ao usuário que as publicou.

- **Exibição de Notícias**: Desenvolvimento de uma seção na página inicial para exibir as últimas notícias, incluindo título, conteúdo e autor.

- **Página de Todas as Notícias**: Criação de uma página dedicada (`ver_todas_noticias.php`) onde todas as notícias são listadas.

- **Dashboard**: Configuração de um dashboard que exibe estatísticas sobre usuários, restaurantes e produtos cadastrados, com um design responsivo e estilizado com Tailwind CSS.

- **Footer**: Adição de um footer com créditos ao desenvolvedor.

## Estrutura do Banco de Dados

- **Tabelas Criadas**: `usuarios`, `ranks`, `itens_cardapio`, `restaurantes`, `produtos`, `noticias`.
- **Relacionamentos**: Definição de chaves estrangeiras para associar as tabelas entre si.

## Estilização

- Utilização do framework **Tailwind CSS** para estilizar os componentes da interface do usuário, garantindo um design responsivo e moderno.

## Segurança

- Implementação de práticas de segurança como consultas preparadas para evitar injeção de SQL e uso de `htmlspecialchars` para prevenir ataques XSS.
