4- Perguntas teóricas

# 4.1 API Resources no Laravel
Explique:
### Qual é o objetivo de utilizar API Resources?


- Resposta: API Resources servem para modificar e padronizar a estrutura de retorno de dados, semelhante ao que é feito em um DTO, permitindo filtrar/omitir informações enviando apenas o retorno desejado

### Em quais situações eles são úteis no desenvolvimento de APIs?

- Resposta: Em situações de desenvolvimento, serve para ocultar/omitir dados sensíveis que podem ser retornados de uma requisição, padronizar o retorno de rotas, mesmo que sejam feitas alterações em ambientes internos, retornando apenas os campos desejados já definidos, filtrar dados enviados de acordo com condicionais (se uma condição é cumprida, retorna por padrão um formato condicionado).


# 4.2 Organização de Validação em Laravel
### Explique as vantagens de utilizar classes específicas para validação de dados, em vez de realizar validações diretamente no controller. Considere aspectos como:
### * Organização do código;
### * Manutenção;
### * Reutilização.

- Resposta: preservar a organização e padronização do projeto, onde o controller deve realizar apenas o papel dele, de direcionar o fluxo da aplicação, mantendo o controller organizado e menos poluído, permitindo realizar com mais facilidade a manutenção do código, reaproveitando a validação em mais de um método do controller, evitando de se realizar manutenção em diversos trechos que usam a mesma regra

# 4.3 Testes Automatizados no Laravel
Responda às seguintes perguntas:


### 1. Para que servem testes automatizados em uma aplicação Laravel?

- Resposta: Serve para emular determinado cenário, visando garantir que o código está íntegro e realiza seu propósito com o retorno adequado para diferentes situações, economizando bastante tempo em casos de testes diversos, onde são feitas alterações e se espera o mesmo retorno, ou em caso de diferentes retornos, torna, se fácil padronizar o resultado esperado e validar.

### 2. Caso você precise testar um endpoint da API, explique como você implementaria esse teste utilizando PHPUnit no Laravel, incluindo:

### * onde o teste seria criado
- Resposta: O teste seria criado dentro do diretório de te testes `tests/Features`

### * como o endpoint seria testado
- Resposta: Dentro do arquivo criado para testes, é instanciado uma simulação de uma requisição https, e realiza a chamada de uma rota, em casos onde é necessário se passar um body, é passado um valor genérico para testes e depois o retorno da rota é comparado com o retorno esperado que é definido no arquivo.

### * como executar os testes no projeto
- Resposta: Os testes podem ser executados todos juntos com o comando `php artisan test`, ou de forma individual chamado diretamente o arquivo de teste e o método específico que se quer testar `php artisan test tests/Feature/ArquivoGenericoTest.php --filter=test_validacao_de_teste_generica`
