# Formatting Your Poems with Markdown

This guide explains how to format your poetry files (`.md` or `.txt`) to take full advantage of the Poetry Site platform's minimalist design and features.

## 1. The Poem Title
To ensure your poem's title is displayed correctly in the archive and at the top of the page, use a single `#` followed by a space.

**Example:**
```markdown
# The Silent River
```
*Note: The platform extracts this line to create the links in your archive.*

## 2. Stanzas and Spacing
Poetry relies on specific spacing. The platform handles this as follows:
- **Single Line Break:** Press `Enter` once to move to the next line within a stanza.
- **New Stanza:** Press `Enter` twice (leave an empty line) to create a new stanza. Each stanza is wrapped in a container that allows for smooth "reveal-on-scroll" animations.

**Example:**
```markdown
First line of the first stanza.
Second line of the same stanza.

This is now a new stanza
after an empty line.
```

## 3. Emphasis and Style
You can add subtle styling to your text using standard Markdown:
- **Italics:** Wrap text in single asterisks `*like this*` or underscores `_like this_`.
- **Bold:** Wrap text in double asterisks `**like this**` or underscores `__like this__`.

**Example:**
```markdown
The *wind* whispered through the **ancient** trees.
```

## 4. Sub-headings (Optional)
If your poem is divided into parts or chapters, you can use sub-headings.
- Use `## ` for a major section.
- Use `### ` for a smaller sub-section or date/location note.

**Example:**
```markdown
## Part I: The Awakening
...
### London, 1842
```

## 5. Bilingual Content (Naming Convention)
If you want to provide translations for your poems:
1. Upload the original file (e.g., `morning.md`).
2. Upload the translation with a language suffix:
   - For English: `morning_en.md`
   - For Greek: `morning_el.md`

The platform will automatically switch between these files when the visitor changes the site language.

---

# Οδηγίες Σύνταξης Ποιημάτων (Markdown)

Αυτός ο οδηγός εξηγεί πώς να διαμορφώσετε τα αρχεία των ποιημάτων σας (`.md` ή `.txt`) για να εκμεταλλευτείτε πλήρως τις δυνατότητες της πλατφόρμας.

## 1. Τίτλος Ποιήματος
Για να εμφανίζεται σωστά ο τίτλος στο αρχείο και στην κορυφή της σελίδας, χρησιμοποιήστε το σύμβολο `#` ακολουθούμενο από ένα κενό.

**Παράδειγμα:**
```markdown
# Το Σιωπηλό Ποτάμι
```

## 2. Στροφές και Αποστάσεις
- **Απλή αλλαγή γραμμής:** Πατήστε `Enter` μία φορά για να πάτε στην επόμενη γραμμή εντός της ίδιας στροφής.
- **Νέα Στροφή:** Πατήστε `Enter` δύο φορές (αφήστε μια κενή γραμμή). Κάθε στροφή εμφανίζεται με το εφέ "reveal-on-scroll" καθώς ο αναγνώστης σκρολάρει.

**Παράδειγμα:**
```markdown
Πρώτος στίχος της πρώτης στροφής.
Δεύτερος στίχος της ίδιας στροφής.

Εδώ ξεκινάει μια νέα στροφή
μετά από μια κενή γραμμή.
```

## 3. Έμφαση και Στυλ
- **Πλάγια γράμματα:** Βάλτε το κείμενο ανάμεσα σε αστερίσκους `*έτσι*` ή κάτω παύλες `_έτσι_`.
- **Έντονα γράμματα:** Βάλτε το κείμενο ανάμεσα σε διπλούς αστερίσκους `**έτσι**` ή διπλές κάτω παύλες `__έτσι__`.

## 4. Υπότιτλοι και Σημειώσεις
Αν το ποίημα χωρίζεται σε μέρη ή θέλετε να προσθέσετε ημερομηνία/τοποθεσία:
- Χρησιμοποιήστε `## ` για κύρια μέρη.
- Χρησιμοποιήστε `### ` για ημερομηνίες ή τοποθεσίες.

**Παράδειγμα:**
```markdown
## Μέρος Α: Η Αφύπνιση
...
### Αθήνα, 2024
```

## 5. Δίγλωσσο Περιεχόμενο (Ονοματολογία)
Για τις μεταφράσεις των ποιημάτων σας:
1. Ανεβάστε το πρωτότυπο (π.χ. `proi.md`).
2. Ανεβάστε τη μετάφραση με την κατάληξη της γλώσσας:
   - Για Αγγλικά: `proi_en.md`
   - Για Ελληνικά: `proi_el.md`

Η πλατφόρμα θα εναλλάσσει αυτόματα τα αρχεία όταν ο επισκέπτης αλλάζει τη γλώσσα του site.
