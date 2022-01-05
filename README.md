
# LottoPlugin [![https://poggit.pmmp.io/p/LottoPlugin/](https://img.shields.io/badge/Made%20with-%F0%9F%92%96-red)]()
# Features
> - Compatibility with Pocketmine 4.X.X
> - Full Customization
> - Automatic Launch
> - Automatic Draw
# Usage
## Initialisation
You have to install [Pocketmine](https://github.com/pmmp/PocketMine-MP) 4.X.X version.<br>
You have to install [EconomyAPI](https://poggit.pmmp.io/p/EconomyAPI/) in your PMMP Server. 
## Commands

 - **Lotto** :
	 - Description : View the remaining lotto time and the current total amount.
	 - Permission : None.
 - **Ticket** :
	 - Description : See the remaining lotto time and the current total sum.
	 - Permission : None.	
	 - **buy** :
		 - Description : Buy tickets.
		 - Usage : `/ticket buy <amount>`
	- **info** :
		 - Description : See your tickets.
		 - Usage : `/ticket info`

## Configuration of config.yml
### Lotto Values :
> | Name  | Description | Default |
> |--|--|--|
> | ticket-price |Unity's ticket price | 100
> | repeating-time |The recurrence of lotto reminders (minutes)| 15
### Success :
 - `win`
> | Name  | Description | Type |
> |--|--|--|
> | messages.success.win |The message when a <br> person wins the lotto | Success |
>
> | Parameters: | Replaced by |
> |--|--|
> | {winner} | Winner's name | 
> | {cashprize} | Current total sum | 
> | {participants} | Participants's number| 
 - `no-winner`
> | Name  | Description | Type |
> |--|--|--|
> | messages.success.no-winner | The message when there's none participant | Success |
 - `repeating`
> | Name  | Description | Type |
> |--|--|--|
> | messages.success.repeating | The message that repeats itself after a defined time | Success |
> 
> | Parameters: | Replaced by |
> |--|--|
> | {timeleft} | Temps restant | 
> | {cashprize} | Current total sum | 
 - `ticket-buy`
> | Name  | Description | Type |
> |--|--|--|
> | messages.success.ticket-buy | The message when you buy tickets | Success |
> 
> | Parameters: | Replaced by |
> |--|--|
> | {tickets} | Tickets amount | 
> | {amount} | Sum of buying | 
 - `info-buyed`
> | Name  | Description | Type |
> |--|--|--|
> | messages.success.info-buyed| The message when the person looks <br> his ticket number and has some | Success |
> 
> | Parameters: | Replaced by |
> |--|--|
> | {tickets} |Tickets amount | 
> | {amount} | Sum of buying | 
 - `info-not-buyed`
> | Name  | Description | Type |
> |--|--|--|
> | messages.success.info-not-buyed| The message when the person looks <br> his ticket number and does not have one | Success |
 - `loto-command-answer`
> | Name  | Description | Type |
> |--|--|--|
> | messages.success.loto-command-answer| The message when the person looks at the remaining lotto time and its sum | Success |
> 
> | Parameters: | Replaced by |
> |--|--|
> | {timeleft} | Time left | 
> | {cashprize} | Current total sum | 
### Errors :
 - `cant-buy-under-one`
> | Name  | Description | Type |
> |--|--|--|
> | messages.errors.cant-buy-under-one | The message when the person enters a number less than 1 of tickets to buy | Error |
> 
> | Parameters: | Replaced by |
> |--|--|
> | {tickets} |Input number | 
 - `not-enough-money`
> | Name  | Description | Type |
> |--|--|--|
> | messages.errors.not-enough-money |The message when the person <br> doesn't have enough money | Error |
> 
> | Parameters: | Replaced by |
> |--|--|
> | {tickets} | Input number | 
 - `didnt-enter-amount`
> | Name  | Description | Type |
> |--|--|--|
> | messages.errors.didnt-enter-amount | The message when the person has <br> not entered an amount to buy| Error |
 - `usage`
> | Name  | Description | Type |
> |--|--|--|
> | messages.errors.usage |The message when the person <br> does not enter **buy** or **info** in the order| Error

# Changelog
> ## **2.0.0**
>    * Now supports PocketMine-MP 4.X.X.
# Credits
* Nyrok :
  - [Github](https://github.com/Nyrok) ![GitHub followers](https://img.shields.io/github/followers/Nyrok?style=social)
  - [Twitter](https://twitter.com/@Nyrok10) ![Twitter Follow](https://img.shields.io/twitter/follow/Nyrok10?style=social)
> Don't forget to Star this open-source repo ! ![GitHub Repo stars](https://img.shields.io/github/stars/Nyrok/LottoPlugin?style=social)
# License
Apache-2.0
