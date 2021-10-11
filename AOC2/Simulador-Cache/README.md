# Simulação de Cache
Segundo trabalho da cadeira de Arquitetura e Organização de Computadores II, ministrada por Marcelo Schiavon Porto.

## Membros
* [Willians Silva](https://github.com/wiliansSilva)
* [Carlos André Feldmann Júnior](https://feldmann.dev)
## Objetivo
Simular um cache, lendo uma lista de endereços de 32bits de uma arquivo binário, e coletar dados sobre acertos e erros no cache.


## Como Compilar/Executar

### Compilação
#### Dependencias
Java 8 -  programa foi testado com o build `OpenJDK Runtime Environment (build 1.8.0_232-b09)
`, porém é esperado funcionar com qualquer build do Java 8.

[Maven](https://maven.apache.org/) - Gerenciador de dependencias/build para o java.

Para compilar executar o comando
```mvn clean package```, onde será construido o arquivo SimuladorCache.jar na pasta ./target.

### Execução

É esperado para a execução básica do programa os seguintes argumentos
``java -jar SimuladorCache.jar <nsets> <bsize> <assoc> <substituição> <flag_saida> arquivo_de_entrada`` 

Onde:
* nsets = é a quantidade de conjutos
* bsize = o tamanho do bloco em bytes
* assoc = é o número de vias por conjunto
* substituição = a política de substituição aceita(RANDOM(R),FIFO(F),LRU(L))
* flag_saida = Se 1 a saída é feita de forma simples, ideal para automatização
* arquivo_de_entrada = Arquivo binário com endereços de 32 bits

É possível adicionar no final do comandos os seguintes argumentos:
 * --history = Mostra o histórico de escritas por conjunto
 * --debug = Informações de debug do programa
 