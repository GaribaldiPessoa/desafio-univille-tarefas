# 📄 Documento de Requisitos de Produto (PRD) e Especificação de API

**Produto:** Portal de Tarefas | Univille (MVP)  
**Autor:** Garibaldi Pessoa  
**Data:** Fevereiro 2026  

---

## 1. Visão do Produto
O Portal de Tarefas é uma solução acadêmica desenhada para reduzir o atrito na gestão de atividades diárias de alunos, professores e colaboradores da Univille. O foco deste MVP é validar a usabilidade (UX) em operações essenciais (CRUD) e introduzir a mecânica colaborativa de transferência de responsabilidades (delegação de tarefas).

## 2. Histórias de Usuário (User Stories)

* **Autenticação:** Como usuário, quero poder criar uma conta ou fazer login para ter um ambiente seguro e isolado para as minhas tarefas.
* **Gestão de Tarefas:** Como usuário, quero criar, visualizar, marcar como concluídas e remover tarefas de forma rápida, para manter a minha rotina organizada.
* **Colaboração:** Como usuário, quero poder transferir uma tarefa para outro membro da instituição pesquisando o seu e-mail, para delegar atividades de forma eficiente.

## 3. Regras de Negócio e UX Aplicadas

1. **Validação *Client-Side*:** A validação de senhas (criação de conta) deve ocorrer em tempo real no *frontend* para evitar requisições desnecessárias ao servidor e frustração do usuário.
2. **Lei de Fitts:** A área de clique (conclusão) da tarefa deve expandir-se por toda a linha (card) e não apenas no *checkbox*.
3. **Prevenção de Erros na Transferência:** * O sistema não deve listar o usuário autenticado no modal de transferência (não é possível transferir para si mesmo).
   * O campo de busca de e-mail deve possuir *autocomplete* visual com confirmação via tecla `TAB`.
4. **Feedbacks Não-Obstrutivos:** Ações de sucesso ou erro devem gerar notificações flutuantes (*Toasts*) em vez de interrupções nativas do navegador (*Alerts*).

---

## 4. Contratos de API (JSON & Endpoints)

A comunicação entre o Vue.js e o Laravel obedece aos seguintes contratos principais:

### 4.1. Autenticação (Cadastro)
**`POST /api/register`**
* **Payload (Request):**
```json
{
  "name": "Professor Silva",
  "email": "professor.silva@univille.br",
  "password": "senha_segura"
}
```
* **Resposta de Sucesso (200 OK):**
```json
{
  "user": {
    "id": 1,
    "name": "Professor Silva",
    "email": "professor.silva@univille.br"
  },
  "access_token": "1|ZmlHEEIN6NnzNutoDWQHL8Ysw...",
  "token_type": "Bearer"
}
```

### 4.2. Criação de Tarefa
**`POST /api/tasks`** *(Requer Bearer Token)*
* **Payload (Request):**
```json
{
  "title": "Corrigir provas de Engenharia de Software"
}
```
* **Resposta de Sucesso (201 Created):**
```json
{
  "id": 15,
  "title": "Corrigir provas de Engenharia de Software",
  "is_completed": false,
  "user_id": 1,
  "created_at": "2026-02-26T14:00:00.000000Z"
}
```

### 4.3. Listagem de Usuários (Busca Dinâmica)
**`GET /api/users?search=prof&page=1`** *(Requer Bearer Token)*
* **Resposta (Paginação Laravel):**
```json
{
  "current_page": 1,
  "data": [
    {
      "id": 1,
      "name": "Professor Silva",
      "email": "professor.silva@univille.br"
    }
  ],
  "first_page_url": "http://localhost/api/users?page=1",
  "last_page": 1,
  "total": 1
}
```

### 4.4. Transferência de Tarefa
**`PUT /api/tasks/{id}/transfer`** *(Requer Bearer Token)*
* **Payload (Request):**
```json
{
  "email": "secretaria@univille.br"
}
```
* **Resposta de Sucesso (200 OK):**
```json
{
  "message": "Tarefa transferida com sucesso"
}
```

---

## 5. Roadmap Futuro (Próximas Iterações)
* Implementação de recuperação de senha via e-mail.
* Adição de Prazos (*Due Dates*) nas tarefas com ordenação por urgência.
* Categorização de tarefas via *Tags* (Ex: "Urgente", "Pesquisa", "Administrativo").
* Notificações via e-mail quando uma tarefa for transferida para um usuário.
