# LotoPlugin [![https://poggit.pmmp.io/p/LotoPlugin/](https://img.shields.io/badge/Made%20with-%F0%9F%92%96-red)]()
# Features
> - Compatibilité avec Pocketmine 3.X.X
> - Customisation complète
> - Lancement automatique dès le démarrage
> - Tirage au sort automatique
# Utilisation
## Initialisation
You have to install [Pocketmine](https://github.com/pmmp/PocketMine-MP) 3.X.X version.
You have to install [EconomyAPI](https://poggit.pmmp.io/p/EconomyAPI/) in your PMMP Server. 
## Commandes

 - **Loto** :
	 - Description : Voir le temps restant du loto et la somme totale actuelle.
	 - Permission : Aucune.
 - **Ticket** :
	 - Description : Voir le temps restant du loto et la somme totale actuelle.
	 - Permission : Aucune.	
	 - **buy** :
		 - Description : Acheter un nombre de ticket.
		 - Utilisation : `/ticket buy <nombre>`
	- **info** :
		 - Description : Voir son nombre de ticket.
		 - Utilisation : `/ticket info`

## Configuration of config.yml
### Success :
 - `win`
> | Name  | Description | Type |
> |--|--|--|--|--|
> | messages.success.win | Le message lorsqu'une <br>personne gagne le loto | Success |
>
> | Parameters: | Replaced by |
> |--|--|--|--|--|
> | {winner} | Le nom du gagnant | 
> | {cashprize} | La somme total du loto| 
> | {participants} | Le nombre de participant| 
 - `no-winner`
> | Name  | Description | Type |
> |--|--|--|--|--|
> | messages.success.no-winner | Le message lorsqu'il <br>n'y a aucun participant | Success |
 - `repeating`
> | Name  | Description | Type |
> |--|--|--|--|--|
> | messages.success.repeating | Le message qui se répète<br> suivant un temps définit | Success |
> 
> | Parameters: | Replaced by |
> |--|--|--|--|--|
> | {timeleft} | Temps restant | 
> | {cashprize} | La somme total du loto | 
 - `ticket-buy`
> | Name  | Description | Type |
> |--|--|--|--|--|
> | messages.success.ticket-buy | Le message quand l'on <br>achète un/des ticket(s) | Success |
> 
> | Parameters: | Replaced by |
> |--|--|--|--|--|
> | {unity} |Nombre de ticket | 
> | {amount} | Somme de l'achat | 
 - `info-buyed`
> | Name  | Description | Type |
> |--|--|--|--|--|
> | messages.success.info-buyed| Le message quand la personne regarde <br>son nombre de ticket et en possède | Success |
> 
> | Parameters: | Replaced by |
> |--|--|--|--|--|
> | {tickets} |Nombre de ticket | 
> | {amount} | Somme des tickets | 
 - `info-not-buyed`
> | Name  | Description | Type |
> |--|--|--|--|--|
> | messages.success.info-not-buyed| Le message quand la personne regarde <br>son nombre de ticket et n'en 
> possède pas | Success |
 - `loto-command-answer`
> | Name  | Description | Type |
> |--|--|--|--|--|
> | messages.success.loto-command-answer| Le message quand la personne regarde <br>le temps restant du loto et sa somme | Success |
> 
> | Parameters: | Replaced by |
> |--|--|--|--|--|
> | {timeleft} | Temps restant | 
> | {cashprize} | La somme total du loto | 
### Errors :
 - `cant-buy-under-one`
> | Name  | Description | Type |
> |--|--|--|--|--|
> | messages.errors.cant-buy-under-one | Le message quand la personne entre un <br> nombre inférieur à 1 de ticket à acheter | Error |
> 
> | Parameters: | Replaced by |
> |--|--|--|--|--|
> | {tickets} |Nombre entré | 
 - `not-enough-money`
> | Name  | Description | Type |
> |--|--|--|--|--|
> | messages.errors.not-enough-money | Le message quand la personne<br>n'a pas assez d'argent  | Error |
> 
> | Parameters: | Replaced by |
> |--|--|--|--|--|
> | {tickets} |Nombre de ticket tenté d'acheter | 
 - `didnt-enter-amount`
> | Name  | Description | Type |
> |--|--|--|--|--|
> | messages.errors.didnt-enter-amount | Le message quand la personne n'a <br> pas entré de montant à acheter  | Error |
 - `usage`
> | Name  | Description | Type |
> |--|--|--|--|--|
> | messages.errors.usage | Le message quand la personne <br> n'entre ni **buy** ni **info** dans la commande | Error

# Changelog
> ## **1.0.0**
>    * First release !
# Credits
* Nyrok :
  - [Github](https://github.com/Nyrok) ![GitHub followers](https://img.shields.io/github/followers/Nyrok?style=social)
  - [Twitter](https://twitter.com/@Nyrok10) ![Twitter Follow](https://img.shields.io/twitter/follow/Nyrok10?style=social)
> Don't forget to Star this open-source repo ! ![GitHub Repo stars](https://img.shields.io/github/stars/Nyrok/LotoPlugin?style=social)
# License
Apache-2.0
