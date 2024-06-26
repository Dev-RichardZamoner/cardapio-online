# Cardápio Digital Online

## Atualizações Recentes

- **Menu Lateral**: Implementação de um menu lateral fixo com opções de navegação para diferentes seções do site, incluindo a adição de notícias e visualização de produtos e restaurantes cadastrados.

- **Listagem de Produtos e Restaurantes**: Adição de funcionalidades para listar produtos e restaurantes cadastrados, com opções para visualizar mais entradas.

- **Rodapé**: Inclusão de um rodapé centralizado no final de cada página, informando que foi desenvolvido pela IA.

## Funcionalidades Implementadas

- **Sistema de Login**: Verificação de login e obtenção de informações como e-mail e rank do usuário.

- **Publicação de Notícias**: Permite que usuários logados publiquem notícias, que são adicionadas ao banco de dados e associadas ao usuário que as publicou.

- **Exibição de Notícias**: Seção na página inicial para exibir as últimas notícias, incluindo título, conteúdo e autor.

- **Página de Todas as Notícias**: (`ver_todas_noticias.php`) para listar todas as notícias.

- **Dashboard**: Exibe estatísticas sobre usuários, restaurantes e produtos cadastrados, com design responsivo em Tailwind CSS.

## Estrutura do Banco de Dados

- **Tabelas**: `usuarios`, `ranks`, `itens_cardapio`, `restaurantes`, `produtos`, `noticias`.
- **Relacionamentos**: Chaves estrangeiras para associar tabelas.

## Estilização

- **Tailwind CSS**: Utilizado para estilizar componentes da interface do usuário, garantindo responsividade e design moderno.

## Segurança

- **Práticas de Segurança**: Consultas preparadas para evitar injeção de SQL e `htmlspecialchars` para prevenir ataques XSS.

## Ainda em Desenvolvimento

Este projeto está em contínuo desenvolvimento, com novas funcionalidades sendo adicionadas regularmente para melhorar a experiência do usuário e a segurança do sistema.

## Imagens do Site

- **Página Inicial**:
  !Página Inicial
  ![Index.php](image.png)

- **Produtos Cadastrados**:
  !Produtos Cadastrados
  ![Todos Cadastros](image-2.png)



- **Sistema de Login**:
  !Sistema de Login!
  ![Login](image-1.png)

- **Publicação de Notícias**:
  !Publicação de Notícias
  ![Noticias](image-3.png)


