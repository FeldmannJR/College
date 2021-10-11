#!/bin/bash
# Este arquivo converte .txt em .bin, é esperado um inteiro em cada linha do .txt, cada linha do binário é códificada
# em binário de 32bits e empurrado no final do arquivo com mesmo nome porem com a extensão .bin
FILE=$1
filename="${FILE%.*}"
# Verifica se arquivo existe
if [ ! -f $FILE ]; then
  echo "Arquivo não encontrado"
  exit 0
fi
# Pega o mesmo arquivo com extensão .bin
outputFile="${filename}.bin"
# Se já existir remove
rm -rf $outputFile

echo "Convertendo $FILE em $outputFile"
# Loop cada linha do arquivo
cat $FILE | while read j
do
  # Regex se é um número válido, não verifica overflow
  if [[ $j =~ ^-?[0-9]+$ ]]; then
    printf "0: %.8x" $j | xxd -r -g0 >>$outputFile
    echo "Escrevendo $j no arquivo"
  else
    echo "$j não é um integer válido"
  fi
done
