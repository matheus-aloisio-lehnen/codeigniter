# CodeIgniter 4 Boilerplate

Boilerplate simples e bem estruturado para projetos backend em **CodeIgniter 4**, focado em
organização, testabilidade e evolução segura em sistemas em produção.

Este repositório não tenta reinventar o framework nem aplicar arquitetura acadêmica.
A proposta é servir como **base sólida** para projetos reais, onde clareza, previsibilidade
e responsabilidade com produção importam mais do que abstrações excessivas.

---

## 🎯 Objetivo

- Servir como ponto de partida para APIs e backends em PHP
- Facilitar manutenção e evolução do código ao longo do tempo
- Incentivar testes automatizados desde o início
- Manter o projeto simples, legível e fácil de entender por novos desenvolvedores

---

## 🧱 Estrutura

O projeto segue a estrutura padrão do CodeIgniter 4, com alguns cuidados adicionais:

- Separação clara entre **Controllers**, **Use Cases** e **Models**
- Regras de negócio fora dos controllers
- Controllers focados apenas em entrada/saída (HTTP)
- Uso consciente de serviços e helpers
- Camada de domínio simples, sem acoplamento desnecessário

A ideia é evitar “fat controllers” e concentrar a lógica em classes testáveis.

---

## ⚙️ Requisitos

- PHP 8.1 ou superior
- Composer
- Extensões PHP habilitadas conforme o CodeIgniter 4

---

## 🚀 Como executar o projeto

Instale as dependências:

- composer install

Configure o ambiente:

- cp env .env
- php spark key:generate

Suba o servidor local:

- php spark serve

O servidor ficará disponível em:

- http://localhost:8080

---

## 🧪 Como rodar os testes

O projeto já vem preparado para testes automatizados com PHPUnit.

Para rodar os testes:

vendor/bin/phpunit

---

## 📄 Licença

- Este projeto está licenciado sob a licença MIT.


