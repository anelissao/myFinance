@component('mail::message')
# Alerte : Dépassement d'objectif financier

Bonjour {{ Auth::user()->name }},

Vous avez dépassé le montant cible de l'objectif financier suivant :

**Objectif :** {{ $goal->name }}  
**Montant cible :** {{ number_format($goal->target_amount, 2) }} €  
**Dépenses totales ce mois-ci :** (voir tableau de bord)

Nous vous recommandons de revoir vos dépenses afin de rester aligné avec vos objectifs.

Merci d'utiliser MyFinance !
@endcomponent
