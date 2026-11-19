# Changelog

## 10.0 — 2026-11-08

- Core/Url::getPageNumber() returns false or positive-int
- App::auth()-&gt;userID() returns string since 2023 (return type fixed in AuthInterface on 10/31/2026)
- DC 2.40+
- Use getter methods rather than widget properties as far as possible
- Cope with RTL
- Enforces some verification before registering module

## 9.0 — 2026-08-03

- To soon for that!
- Avoid using magic methods for user preferences and blog settings (2.39+)
- Use MetaRecord::strField(), MetaRecord::intField() and MetaRecord::boolField() whenever is possible
- Avoid using deprecated auth::getOption()
- Review icon colors (DC 2.39+)

## 8.7 — 2026-05-15

- Add support of glaz template set
- Use dotclear.ToolBar rather than old jsToolBar, DC 2.39+

## 8.6.1 — 2026-04-15

- Cope with unexisting $params global variable

## 8.6 — 2026-04-14

- Version de maintenance, sans changement fonctionnel notable relevé dans l’historique.

## 8.5 — 2026-04-07

- Add some classes (content_info, feeds), closes #13
- Add feeds class for feed list container

## 8.4.1 — 2026-03-22

- Version de maintenance, sans changement fonctionnel notable relevé dans l’historique.

## 8.4 — 2026-03-17

- DA -&gt; DC

## 8.3 — 2025-09-22

- Use backend services rather than static calls

## 8.2 — 2025-09-12

- Replace static call to Ctx by context() — thanks JcDenis for advice

## 8.1 — 2025-09-07

- DC 2.36 compliance

## 8.0 — 2025-09-06

- DC 2.36 compliance

## 7.3 — 2025-07-14

- Version de maintenance, sans changement fonctionnel notable relevé dans l’historique.

## 7.2 — 2025-06-01

- Version de maintenance, sans changement fonctionnel notable relevé dans l’historique.

## 7.1 — 2025-05-05

- Use Strong(…) rather than Text('strong', …)

## 7.0 — 2025-04-20

- Use TemplateHelper for template blocks/values DC 2.34+

## 6.1 — 2025-01-11

- Be conservative (will see in future if something more is needed)

## 6.0 — 2025-01-09

- 2.33 compliance
- No need to test content as preg_match pattern is deterministic

## 5.5 — 2024-09-16

- Version de maintenance, sans changement fonctionnel notable relevé dans l’historique.

## 5.4 — 2024-01-27

- Version de maintenance, sans changement fonctionnel notable relevé dans l’historique.

## 5.3 — 2024-01-20

- Version de maintenance, sans changement fonctionnel notable relevé dans l’historique.

## 5.2.1 — 2023-11-11

- Version de maintenance, sans changement fonctionnel notable relevé dans l’historique.

## 5.2 — 2023-11-10

- Use SqlStatement to build queries

## 5.1 — 2023-10-27

- Version de maintenance, sans changement fonctionnel notable relevé dans l’historique.

## 5.0 — 2023-10-25

- Simplify template dir management
- Use template()-&gt;appendPath(…) rather than -&gt;setPath(-&gt;getPath(), …)
- Use instanced item constants rather than interface ones
- Avoid magic method as far as possible
- Use 2.28 Config methods
- 2.28 compliance
- Use match rather than switch

## 4.0 — 2023-08-23

- Dotclear 2.27 compliance, PHP 8.1+

## 3.5 — 2023-07-12

- Use SVG icon rather than PNG

## 3.4.1 — 2023-07-06

- Fix usage of settings

## 3.4 — 2023-07-06

- Add a widget option to display or not link to all authors, close #4

## 3.3.2 — 2023-07-06

- Version de maintenance, sans changement fonctionnel notable relevé dans l’historique.

## 3.3.1 — 2023-06-27

- Add local static $init variable

## 3.3 — 2023-06-22

- Version de maintenance, sans changement fonctionnel notable relevé dans l’historique.

## 3.2 — 2023-06-20

- Use dcCore::app()-&gt;adminurl-&gt;redirect() rather than Http::redirect()

## 3.1 — 2023-06-20

- Don't use admin URLs in help files

## 3.0.2 — 2023-06-12

- Fix type hint

## 3.0.1 — 2023-06-12

- Don't do things if disabled

## 3.0 — 2023-06-12

- Fix English help
- Switch to namespace
- Remove deprecated
- Prepare 2.26
- Take care of some contexts
- Prepare 2.25
- Minified versions
- Move js

## 2.0 — 2022-11-28

- Cope with new install

## 1.13 — 2022-11-19

- 2.24 compliance

## 1.12 — 2022-08-15

- Update default branch in _define.php for alternative repository
- Add alternative repository

## 1.11 — 2021-08-06

- Dotclear 2.19 compliance

## 1.10 — 2020-09-10

- Cope with blog settings for nb of entries per page depending on context
- jQuery3+ compliance

## 1.9 — 2020-05-08

- 2.16+ compliance

## 1.8.2 06-11-2017

- modifs tpl

## 1.8.1 23-07-2015

- modifs tpl
- ajout css

## 1.8 19-07-2015

- ajout breadcrumb
- héritage thème mustek
- dc2.8 requis

## 1.7.1 22-04-2015

- modifs localisation widget
- modifs html5 pour validation
- ajout favoris
- modifs icône

## 1.7 17-12-2014

- compatibilité dc2.7
- html5 (mustek et currywurst)
- mise en place de la barre d'édition pour la description de l'auteur (merci à Nikrou)

## 1.6.1 02-10-2014

- ajout d'un lien vers la liste des auteurs
- modif du widget
- modif de la page listant les auteurs
- ajout aide intégrée
- dc2.6 requis
