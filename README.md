# Portal de Tarefas | Univille 🎓

Bem-vindo ao repositório do MVP do **Gerenciador de Tarefas Acadêmico**, desenvolvido como parte do desafio técnico para a vaga na Univille.

Este projeto não é apenas uma entrega de código, mas uma demonstração de **visão de produto**, **experiência do usuário (UX)** e **arquitetura de software**. A interface foi inteiramente adaptada para a identidade visual da Univille, garantindo aderência imediata à marca.

## 🚀 Principais Entregas e Funcionalidades

Além dos requisitos básicos (CRUD de tarefas, Autenticação e Banco de Dados), o projeto conta com:

* **Filtros e Ordenação Avançados:** Filtro por status (pendente/concluída) e ordenação cronológica.
* **Paginação Backend:** Otimização de performance no Laravel, trazendo dados de forma paginada em vez de sobrecarregar a memória.
* **Transferência de Propriedade:** Possibilidade de transferir uma tarefa para outro usuário do sistema.
* **Busca com Autocomplete:** Modal de transferência otimizado com busca em tempo real por e-mail e atalho de preenchimento via tecla `TAB`.
* **UX/UI Nível Produção:**
  * Toasts flutuantes para feedback de ações (sucesso/erro), substituindo alertas nativos invasivos.
  * Validação de senhas em tempo real na tela de criação de conta.
  * Otimização da Lei de Fitts: A área clicável da tarefa abrange toda a linha, facilitando o uso em dispositivos móveis.
  * Design totalmente responsivo utilizando o moderno Tailwind CSS 4.0.

## 🛠️ Stack Tecnológico

* **Backend:** PHP 8+ com Laravel 11. Banco de dados MySQL gerenciado via Docker (Laravel Sail).
* **Frontend:** Vue.js 3 (Composition API) com Vite, Vue Router e Axios.
* **Estilização:** Tailwind CSS v4.0.

---

## ⚙️ Como Rodar o Projeto na Sua Máquina

### 1. Preparando o Motor (Backend - Laravel)
Abra um terminal na pasta `backend` e execute os comandos abaixo. Certifique-se de ter o Docker rodando em sua máquina.

```bash
# Copie o arquivo de configuração
cp .env.example .env

# Instale as dependências do PHP
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php8.3-composer:latest \
    composer install --ignore-platform-reqs

# Suba os containers do Docker (Banco e Servidor)
./vendor/bin/sail up -d

# Gere a chave da aplicação e crie as tabelas no banco de dados
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate
