;=-=-=-=-=-=-=-=-=-=-=-=-=- Instruções do programa =-=-=-=-=-=-=-=-=-=-=
; Ao executar o programa o primeiro numero a ser dado de entrada é o tipo de calculo a realizar, sendo eles:
; 1 = Soma(2 args), 2 = Subtração(2 args), 3 = Multiplicação(2 args), 4 = Divisão(2 args), 5 = Fatorial(1 arg), 6(0 args) = Sair do programa
; Após entrar com a operação é preciso passar os argumentos(args) para realiza-la, após realizar a operação ele voltará ao menu, enquanto não entrar a opção para sair do programa
; Enquanto a letra M estiver piscando, o programa está pedindo o número da operação, enquanto o char > estiver piscando ele está pedindo um operando para realizar algum calculo, se estiver sem nada
; o programa está processando dados.
;=-=-=-=-=-==-=-=-=-=-=-=-=-==-==-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=

; =-=-=-=-=-=-= Definição de pseudo-variaveis =-=-=-=-=-=-=
; Antes de executar o programa ele vai substituir as ocorrencias do nome antes DE EQU pelo endereço depois de EQU
simbolo EQU 245
leitura EQU 246
pular EQU 247
op1 EQU 248
op2 EQU 249
resultado EQU 250
aux EQU 251
aux2 EQU 252
aux3 EQU 253
valor1 EQU 254
valor0 EQU 255
;=-=-==-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=====-=-

LDI 01
STA valor1

;=-=-=-=-==-===-=- MENU =-=-=--=-=-=-=-=-=-=
menu:
LDI 00
STA resultado
LDI menuops
STA pular
LDI 77
STA simbolo

JMP lervalor

menuops:
LDI 62
STA simbolo
LDI 01
SUB leitura
JZ soma
LDI 02
SUB leitura
JZ sub
LDI 03
SUB leitura
JZ mult
LDI 04
SUB leitura
JZ div
LDI 05
SUB leitura
JZ fatorial
LDI 06
SUB leitura
JZ sair
JMP menu

voltamenu: OUT 00
JMP menu

sair:
HLT
;=-=-=-=-=-=-=-=- Fim Menu =-=-=-=-=-=--=-=-=-=
;=========Retornos======;

resultado0:
LDI 0
JMP voltamenu
;=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-;

;=-=-=-=-=-=-=-=- Fatorial =-=-=-=-=-=-=-=-=
fatorial:
LDI fatorial1
STA pular
JMP lervalor

fatorial1:
LDA leitura
JZ resultado0
STA aux
LDI 01
STA aux2

fatorialmult:
LDI fatorialmult2
STA pular
LDA aux
STA op1
LDA aux2
STA op2
LDI 00
STA resultado
JMP multi

fatorialmult2:
LDA resultado
STA aux2
LDA aux
SUB valor1
STA aux
JNZ fatorialmult
LDA aux2
JMP voltamenu

;=-=-=-==-=-=- Fim Fatorial =-=-=-=-=-=-=-=-=

;=-=-=-=-=-=- Inicio Mult =-=-=-=-=-=-=-=
mult:
LDI mult1
STA pular
JMP ler2valor

mult1:
LDI mult2
STA pular
JMP multi

mult2:
LDA resultado
JMP voltamenu

multi:
LDA op1
ADD resultado
STA resultado
LDA op2
SUB valor1
STA op2
JZ @pular
JMP multi
;=-=-=-=-=-= Fim Mult =-=-=-=-=-=-=-=-=

;=-=-=-=-=-= Inicio Divisão =-=-=-=-=
div:
LDI divi
STA pular
JMP ler2valor

div2:
LDA resultado
JMP voltamenu

divi:
LDA op2
JZ resultado0
LDA op1
JN div2
SUB op2
STA op1
JN div2
LDA resultado
ADD valor1
STA resultado
JMP divi
;=-=-=-=-=-= Fim Divisão =-=-=-=-=-=

;=-=-=-==-=- Inicio Soma =-=-=-=-=-=
soma:
LDI soma1
STA pular
JMP ler2valor

soma1:
ADD op1
JMP voltamenu
;=-==-=-=- Fim Soma =-=-=-=-=-=-=

;==-=-=-=- Inicio sub =-=-=-=-=-=
sub:
LDI sub1
STA pular
JMP ler2valor

sub1:
LDA op1
SUB op2
JMP voltamenu

;=-=-=-=-= Fim Sub =-=-=-=-=-=-=

;=-=-=-=-= Inicio Metodos de entrada =-=-=-=-=
ler2valor:
LDA pular
STA aux3
LDI ler2valor2
STA pular
JMP lervalor

ler2valor2:
STA op1
LDI ler2valor3
STA pular
JMP lervalor

ler2valor3:
STA op2
JMP @aux3

lervalor:
LDA simbolo
OUT 2 ;https://github.com/gpsilva2003/NeanderLin/blob/88e0f861f54e394d50d2b096ddc412f910fea072/uSimula.pas#L154-#L158
IN 1
ADD valor0
OUT 3
JZ lervalor
IN 0 
STA leitura
JMP @pular
;=-=-=-= Fim Metodos de entrada =-=-=-=-=-=-=
