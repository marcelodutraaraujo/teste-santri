# SANTRI Teste prático

## Product Price Calculator (B2B)

Biblioteca e API REST em PHP puro para cálculo de preços de produtos em um
cenário de e-commerce B2B de material de construção.

O projeto foi desenvolvido com foco em boas práticas de PHP, arquitetura limpa
e facilidade de execução via Docker.

---

### Tecnologias utilizadas

- PHP 8.1+
- MySQL 8.0
- Composer
- PHPUnit
- Docker & Docker Compose
- PHP puro

--- 

### Arquitetura 

O projeto segue princípios de **SOLID** e utiliza padrões de projeto:

- **Strategy Pattern**
  - Descontos por quantidade
  - Descontos por tipo de cliente
  - Impostos por estado
  - Acréscimos por peso

- **Repository Pattern**
  - Persistência desacoplada usando PDO

- **Cache Interface**
  - Implementação atual em arquivo
  - Preparado para Redis/Memcached

- **Dependency Injection**
  - Componentes desacoplados e testáveis

---

## Como rodar o projeto

### Subir os containers ja pre' definidos no arquivo do docker composer

 - Rodar o docker 
   ```bash
   docker compose up --build 
 
 - Ou para versão anterior 
   ```bash
   docker-compose up --build

## Servidor 

### Endereço de acesso 

http://localhost:8080 

### Endpoint disponível no momento (testar utilizando Insomnia ou Postman)
/api/calculate - method POST

- Formato do body a ser enviado na requisição no formato JSON
{
  "basePrice": 50,
  "quantity": 100,
  "customerType": "varejo",
  "weight": 60,
  "state": "SP"
}

#### OBS.: 
 - basePrice (Preço base do produto)
 - quantity (quantidade)
 - customerType (tipo de cliente para desconto ex: varejo, atacado, revendedor)
 - weight (acrescimo por peso)
 - state (estado para pegar a porcentagem de impostos do estado definido)

- Formato de resposta da requisição em formato JSON
{
  "finalPrice": 1134.36
}

#### OBS.:
 - finalPrice (preço final já calculado) 

## Regras de negócio 
Desconto progressivo:
 - 1–9 unidades: 0%
 - 10–49 unidades: 3%
 - 50+ unidades: 5%
 - Cliente Varejo: +2% de desconto, Atacado 10% de desconto e Revendedor 20% de desconto
 - Produtos acima de 50kg: acréscimo de R$ 15,00
 - ICMS por estado:
   - SP: 18%
   - RJ: 20%

---
