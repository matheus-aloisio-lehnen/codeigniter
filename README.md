# CodeIgniter 4 Boilerplate

Boilerplate simples e bem estruturado para projetos backend em **CodeIgniter 4**, focado em organização, testabilidade e evolução segura de sistemas em produção.

Este repositório serve como uma **base sólida** para projetos reais, onde clareza, previsibilidade e responsabilidade com o ambiente de produção importam mais do que abstrações excessivas.

---

## 🎯 Objetivo

* **Ponto de Partida:** Base pronta para APIs e backends robustos em PHP.
* **Manutenibilidade:** Estrutura que facilita a evolução do código a longo prazo.
* **Test-Ready:** Incentivo ao uso de testes automatizados desde o primeiro dia.
* **Simplicidade:** Código legível e de fácil onboarding para novos desenvolvedores.

---

## 🧱 Estrutura e Arquitetura

O projeto utiliza o padrão **MVC** do CodeIgniter 4, otimizado para evitar o acúmulo de lógica em locais errados (*Fat Controllers*):



[Image of MVC Architecture pattern]


* **Controllers:** Responsáveis apenas pela entrada e saída (HTTP). Validam a requisição e entregam a resposta.
* **Use Cases / Services:** Camada intermediária onde reside a lógica de negócio, garantindo que o código seja reutilizável e testável.
* **Models:** Responsáveis exclusivamente pela interação com o banco de dados e abstração das entidades.
* **Helpers & Libraries:** Funções auxiliares e integrações de terceiros de forma desacoplada.

---

## ⚙️ Requisitos

* **PHP:** 8.4
* **Composer:** Instalado globalmente
* **Extensões:** `intl`, `mbstring`, `curl`, `json`, `xml` (padrão CI4)

---

## 🚀 Como Executar o Projeto

1.  **Instale as dependências:**
    ```bash
    composer install
    ```

2.  **Configure o ambiente:**
    ```bash
    cp env .env
    ```
    *(Não esqueça de configurar suas credenciais de banco de dados e JWT_SECRET no arquivo `.env`)*

3.  **Suba o servidor local:**
    ```bash
    php spark serve
    ```

O servidor ficará disponível em: [http://localhost:8080](http://localhost:8080)

---

## 🧪 Suíte de Testes

O projeto já vem preparado para testes automatizados com **PHPUnit**.

Para rodar todos os testes, utilize o comando:
```bash
vendor/bin/phpunit
