@component('mail::message')
# Alerte Budget - {{ $budget->category->name }}

Bonjour {{ $user->name }},

Nous avons détecté que vous avez dépassé votre budget pour la catégorie **{{ $budget->category->name }}** ce mois-ci.

## Détails du budget

- **Catégorie :** {{ $budget->category->name }}
- **Budget mensuel :** {{ number_format($budget->amount, 2) }} €
- **Dépenses actuelles :** {{ number_format($currentSpending, 2) }} €
- **Dépassement :** {{ number_format($currentSpending - $budget->amount, 2) }} €
- **Pourcentage utilisé :** {{ number_format(($currentSpending / $budget->amount) * 100, 1) }}%

@component('mail::panel')
### Conseils pour réduire vos dépenses

1. Examinez vos dépenses récentes dans cette catégorie
2. Identifiez les dépenses non essentielles
3. Établissez un plan pour le reste du mois
4. Pensez à ajuster votre budget si nécessaire
@endcomponent

@component('mail::button', ['url' => route('budgets.show', $budget)])
Voir les détails du budget
@endcomponent

Vous pouvez ajuster vos paramètres de notification dans votre [tableau de bord]({{ route('dashboard') }}).

Cordialement,<br>
{{ config('app.name') }}
@endcomponent 