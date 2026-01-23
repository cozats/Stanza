# Poetry Site Template

A simple, elegant, and lightning-fast website template for poets who want to share their work without the need for complex systems or databases.

![alt text](https://github.com/cozats/Poetry-Site-Template/blob/main/screens/home-en-light.png) ![alt text](https://github.com/cozats/Poetry-Site-Template/blob/main/screens/home-en-dark.png)
![alt text](https://github.com/cozats/Poetry-Site-Template/blob/main/screens/poem-en-light.png) ![alt text](https://github.com/cozats/Poetry-Site-Template/blob/main/screens/poem-en-dark.png)
![alt text](https://github.com/cozats/Poetry-Site-Template/blob/main/screens/controls.png) ![alt text](https://github.com/cozats/Poetry-Site-Template/blob/main/screens/home-gr-light.png)


## 🌟 Features
- **Minimalist Design:** Focus on the text with beautiful typography (EB Garamond).
- **Light & Dark Mode:** Automatic theme switching for comfortable reading.
- **High Speed:** Built with clean PHP code, no "heavy" frameworks or databases required.
- **Mobile Friendly:** Responsive design that looks great on phones, tablets, and desktops.
- **Bilingual Support:** Full UI and content support for English and Greek.
- **Smart Content Loading:** Automatically serves translated versions of poems if they exist (e.g., `poem_en.md`).
- **Floating Admin Toolbar:** Manage everything directly from your browser with a sleek, hidden dashboard.

## 🛠️ Requirements
To run this website, you only need a hosting provider that supports **PHP** (standard on almost all hosting packages).

## 🚀 Installation Instructions (For non-technical users)

1. **Upload files:** 
   Upload all files from the project folder to your server (via FTP or your hosting provider's File Manager).
   
2. **Customization:** 
   Open the `index.php` file with any text editor. At the very top, you will find the **USER CONFIGURATION** section:
   - **Poet Name:** Your name (appears on the home page and footer).
   - **Site Title:** The name of your collection or website.
   - **UI Language:** Set your default language ('en' or 'el').
   - **Password:** Change the security code for uploading poems.

3. **Done!** 
   Your poetry site is now live.

## ✍️ Management & Adding Poems

1. **Enter Management:**
   - Click the **"Management"** link in the footer.
   - A **Floating Toolbar** will appear in the bottom right corner.
   
2. **Language Selection:**
   - Use the 🌐 icon in the toolbar to switch the entire site (UI and content) between English and Greek.

3. **Adding a Poem:**
   - Click **"+ Add Poem"** in the toolbar.
   - Select your file (.md or .txt) and enter your password.
   - **Formatting:** Use the `#` symbol before your title (e.g., `# My Poem Title`) for automatic styling.

4. **Bilingual Content:**
   - If you have a translation for a poem, simply upload it with the same name followed by `_en` or `_el` (e.g., `autumn.md` and `autumn_en.md`). The site will automatically show the correct version based on the visitor's language.

## 🗑️ Deleting Poems
- Click the **"Delete Poems"** link in the toolbar. 
- You will be taken to the archive list, where a **(delete)** option appears next to each title.

## ✕ Exit
- Click **"✕ Exit"** in the toolbar to deactivate management mode and return to the visitor view.

---

# Poetry Site Template (Ελληνικά)

Ένα απλό, κομψό και εξαιρετικά γρήγορο πρότυπο ιστοσελίδας για ποιητές που θέλουν να μοιραστούν το έργο τους χωρίς την ανάγκη περίπλοκων συστημάτων ή βάσεων δεδομένων.

## 🌟 Χαρακτηριστικά
- **Μινιμαλιστική Σχεδίαση:** Εστίαση στο κείμενο με όμορφη τυπογραφία (EB Garamond).
- **Light & Dark Mode:** Αυτόματη εναλλαγή θέματος για ξεκούραστη ανάγνωση.
- **Υψηλή Ταχύτητα:** Κατασκευασμένο με καθαρό κώδικα PHP, χωρίς ανάγκη για βάσεις δεδομένων.
- **Φιλικό προς Κινητά:** Πλήρως αποκρινόμενο σχέδιο για κάθε συσκευή.
- **Δίγλωσση Υποστήριξη:** Πλήρης υποστήριξη περιβάλλοντος και περιεχομένου σε Ελληνικά και Αγγλικά.
- **Έξυπνη Φόρτωση:** Αυτόματη προβολή μεταφρασμένων ποιημάτων αν υπάρχουν (π.χ. `poem_en.md`).
- **Floating Admin Toolbar:** Διαχείριση τα πάντα απευθείας από τον browser με μια κομψή, πλωτή εργαλειοθήκη.

## 🛠️ Απαιτήσεις
Για να τρέξετε την ιστοσελίδα, χρειάζεστε μόνο ένα πακέτο φιλοξενίας (Hosting) που να υποστηρίζει **PHP**.

## 🚀 Οδηγίες Εγκατάστασης (Για μη τεχνικούς)

1. **Μεταφόρτωση αρχείων:** 
   Ανεβάστε όλα τα αρχεία του φακέλου στον διακομιστή σας (μέσω FTP ή του File Manager του Hosting σας).
   
2. **Προσαρμογή:** 
   Ανοίξτε το αρχείο `index.php` με έναν κειμενογράφο. Στην κορυφή θα βρείτε το τμήμα **ΡΥΘΜΙΣΕΙΣ ΧΡΗΣΤΗ**:
   - **Όνομα Ποιητή:** Το όνομά σας (εμφανίζεται στην αρχική και στο footer).
   - **Τίτλος Συλλογής:** Ο τίτλος της ιστοσελίδας σας.
   - **Γλώσσα:** Ορίστε την προεπιλεγμένη γλώσσα ('el' ή 'en').
   - **Password:** Αλλάξτε τον κωδικό για το ανέβασμα των ποιημάτων.

3. **Έτοιμο!** 
   Η σελίδα σας είναι πλέον ζωντανή.

## ✍️ Διαχείριση & Προσθήκη Ποιημάτων

1. **Είσοδος στη Διαχείριση:**
   - Κάντε κλικ στον σύνδεσμο **"Διαχείριση"** στο κάτω μέρος της σελίδας (Footer).
   - Θα εμφανιστεί μια **πλωτή εργαλειοθήκη (Toolbar)** στην κάτω δεξιά γωνία.
   
2. **Εναλλαγή Γλώσσας:**
   - Χρησιμοποιήστε το εικονίδιο 🌐 στο toolbar για να αλλάξετε τη γλώσσα της ιστοσελίδας και του περιεχομένου.

3. **Προσθήκη Ποιήματος:**
   - Πατήστε **"+ Προσθήκη Ποιήματος"** στο toolbar.
   - Επιλέξτε το αρχείο σας (.md ή .txt) και πληκτρολογήστε τον κωδικό σας.
   - **Σύνταξη:** Χρησιμοποιήστε το σύμβολο `#` πριν από τον τίτλο (π.χ. `# Ο Τίτλος μου`).

4. **Δίγλωσσο Περιεχόμενο:**
   - Αν έχετε μετάφραση για ένα ποίημα, απλά ανεβάστε το με το ίδιο όνομα προσθέτοντας `_en` ή `_el` στο τέλος (π.χ. `fthinoporo.md` και `fthinoporo_en.md`). Το site θα δείχνει αυτόματα τη σωστή έκδοση.

## 🗑️ Διαγραφή Ποιημάτων
- Πατήστε το link **"Διαγραφή Ποιημάτων"** στο toolbar. 
- Θα μεταφερθείτε στη λίστα, όπου δίπλα από κάθε τίτλο υπάρχει η επιλογή **(διαγραφή)**.

## ✕ Έξοδος
- Πατήστε το **"✕ Έξοδος"** στο toolbar για να απενεργοποιήσετε τη διαχείριση και να επιστρέψετε στην προβολή επισκέπτη.

---
*This project was created with the aim of promoting poetry and simplicity on the web. You can use, share, and change it freely.*
