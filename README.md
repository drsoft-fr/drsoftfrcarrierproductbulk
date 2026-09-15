![https://www.drsoft.fr](logo.png)

# drSoft.fr Carrier Product Bulk

Easily select or deselect your carriers in bulk for multiple products in PrestaShop.

---

## 🚚 What does this module do?

This module allows you to:

- Display your carriers and products in filterable tables.
- Select multiple carriers and multiple products at once.
- Add or remove selected carriers for the selected products in a single action.
- Save valuable time managing a large catalog or multiple carriers.

---

## ✅ Main features

- **Carriers Table**:
    - Available filters: reference ID, name, free shipping, active, deleted.
    - Individual or bulk selection (select/deselect all).

- **Products Table**:
    - Powerful filters: product ID, name, reference, supplier, categories, weight, visibility, active status, etc.
    - Individual or bulk selection.

- **Bulk actions**:
    - Add selected carriers to selected products.
    - Remove selected carriers from selected products.

- Dynamic filtering with no full page reloads (HTMX technology).

- Smooth pagination with the ability to choose the number of results per page.

---

## 🧩 Compatibility

- PrestaShop version 8.x and 9.x.
- Multishop compatible.
- Built on modern technologies: Symfony, Twig, HTMX, and AlpineJS.

---

## ⚙️ Installation

1. Place the `drsoftfrcarrierproductbulk` folder into your shop’s `/modules/` directory.
2. Activate the module from your PrestaShop Back Office.
3. Access the dedicated page via Back Office menu > Shipping > Carrier Product Bulk.

---

## 🚀 How to use

1. Open the module’s page.
2. Use the filters to refine your carrier and product lists.
3. Check the carriers and products you want to manage.
4. Click the **Add** button to assign the selected carriers.
5. Click the **Remove** button to unassign carriers from the selected products.
6. Check the confirmation notification after each action.

---

## 💡 Tips for use

- You must select at least one carrier and one product before performing a bulk action.
- Selections are preserved even after filtering.
- Use pagination to easily navigate and adjust the number of displayed items per page.

---

## 📌 Support

Questions or specific needs?  
Contact me at https://drsoft.fr/#contact

---

## 📃 License

Module provided by drSoft.fr and distributed under the MIT License.

---

## 🏁 Ready to simplify your carrier management?

Install and activate the module now to manage your carrier assignments in just a few clicks!

## Makefile

Depuis le dossier du module :

```bash
make build
make package
make clean
```

`make build` installe les dépendances Composer et prépare l’autoloader de
production. `make package` génère ensuite l’archive installable dans `dist/`.
