# Doplňky pro plugin CQPIM

Rozšíření pro [WordPress][1] plugin [CQPIM Project Management][2].

## Přehled vlastností

Plugin `odwp-cqpim_addon` přidává do pluginu __CQPIM__ tyto vlastnosti:

- nové metaboxy pro _CPT_ `cqpim_client`
- tyto metaboxy jsou promítnuty do admin. tabulky s klienty (včetně řazení a filtrování).
- nové sloupečky (včetně řazení) a filtrování pro admin. tabulku s fakturami

## TODO

- [x] dokončit admin. tabulku klienti
  - [x] řazení u nových sloupců
  - [x] filtrování dle typu služby
    - [x] po nastavení filtru a obnovení stránky není selectbox správně nastaven
  - [x] filtrování dle PSČ finančního úřadu
- [ ] dokončit úpravy pro admin. tabulku faktury
  - [x] přidat filtr dle stavu (jen zaplaceno, nezaplaceno)
    - [ ] __FIXME__ - na ostrém serveru to nefunguje!
  - [ ] ~~přidat filtr dle variabilního symbolu faktury~~ (__zrušeno__)
- [x] přidat po aktivaci pluginu test na to, že je nainstalován __CQPIM__
- [x] upravit vstupní formulář (_front-end_) - vytvořeno jako *shortcode* `[odwpca_frontend_form]`

[1]: https://www.wordpress.org/
[2]: http://www.cqpim.uk/
