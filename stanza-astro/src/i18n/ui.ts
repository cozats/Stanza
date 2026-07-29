export type Lang = 'en' | 'el';

export type UIKey =
  | 'lang_toggle'
  | 'collections_title'
  | 'no_collections'
  | 'no_poems'
  | 'add_collection'
  | 'author_name_label'
  | 'collection_title_label'
  | 'btn_create'
  | 'btn_cancel'
  | 'btn_login'
  | 'btn_save'
  | 'pwd_label'
  | 'management'
  | 'exit'
  | 'theme_dark'
  | 'theme_light'
  | 'msg_created'
  | 'msg_error'
  | 'msg_saved'
  | 'msg_deleted'
  | 'msg_del_error'
  | 'about_link'
  | 'home'
  | 'profile'
  | 'contents'
  | 'edit_profile'
  | 'edit_collection'
  | 'bio_label'
  | 'photo_label'
  | 'delete_collection'
  | 'confirm_delete'
  | 'choose_file'
  | 'select_collection'
  | 'new_password_label'
  | 'confirm_password_label'
  | 'msg_pwd_mismatch'
  | 'poet_name'
  | 'site_title'
  | 'archive'
  | 'delete_inline'
  | 'confirm_delete_poem'
  | 'back_to_archive'
  | 'view_poems'
  | 'add_poem'
  | 'delete_poems'
  | 'upload_title'
  | 'file_label'
  | 'pwd_ph'
  | 'btn_upload'
  | 'poems_label'
  | 'msg_pwd_err'
  | 'msg_upload_err'
  | 'msg_ext_err'
  | 'msg_size_err'
  | 'msg_success'
  | 'msg_move_err'
  | 'msg_del_success'
  | 'msg_del_err'
  | 'welcome_msg'
  | 'sort'
  | 'sort_alpha'
  | 'sort_latest'
  | 'align'
  | 'align_left'
  | 'align_center'
  | 'align_right';

export const ui: Record<Lang, Record<UIKey, string>> = {
  el: {
    lang_toggle: 'English',
    collections_title: 'Συλλογές',
    no_collections: 'Δεν υπάρχουν ακόμη δημοσιευμένες συλλογές.',
    no_poems: 'Δεν βρέθηκαν ποιήματα. Ανεβάστε ή γράψτε ένα',
    add_collection: 'Νέα Συλλογή',
    author_name_label: 'Όνομα Ποιητή',
    collection_title_label: 'Τίτλος Συλλογής',
    btn_create: 'Δημιουργία',
    btn_cancel: 'Ακύρωση',
    btn_login: 'Είσοδος',
    btn_save: 'Αποθήκευση',
    pwd_label: 'Κωδικός',
    management: 'Διαχείριση',
    exit: 'Έξοδος',
    theme_dark: 'Σκοτάδι',
    theme_light: 'Φως',
    msg_created: 'Η συλλογή δημιουργήθηκε.',
    msg_error: 'Σφάλμα.',
    msg_saved: 'Όλες οι αλλαγές αποθηκεύτηκαν.',
    msg_deleted: 'Η συλλογή διαγράφηκε.',
    msg_del_error: 'Σφάλμα κατά τη διαγραφή.',
    about_link: 'Σχετικά με το Stanza',
    home: 'Αρχική',
    profile: 'Προφίλ',
    contents: 'Περιεχόμενα',
    edit_profile: 'Επεξεργασία Προφίλ',
    edit_collection: 'Επεξεργασία Συλλογής',
    bio_label: 'Βιογραφικό',
    photo_label: 'Φωτογραφία',
    delete_collection: 'Διαγραφή',
    confirm_delete: 'Είστε σίγουροι ότι θέλετε να διαγράψετε αυτή τη συλλογή;',
    choose_file: 'Επιλογή αρχείου',
    select_collection: 'Επιλέξτε συλλογή',
    new_password_label: 'Νέος Κωδικός',
    confirm_password_label: 'Επαλήθευση Κωδικού',
    msg_pwd_mismatch: 'Οι κωδικοί δεν ταιριάζουν.',
    poet_name: 'Όνομα Ποιητή',
    site_title: 'Τίτλος Συλλογής',
    archive: 'Συλλογή',
    delete_inline: '(διαγραφή)',
    confirm_delete_poem: 'Είστε σίγουροι για τη διαγραφή;',
    back_to_archive: '← Επιστροφή στη Συλλογή',
    view_poems: 'Δείτε τα ποιήματα',
    add_poem: 'Προσθήκη Ποιήματος',
    delete_poems: 'Διαγραφή Ποιημάτων',
    upload_title: 'Ανεβάστε νέο ποίημα',
    file_label: 'Επιλογή αρχείου (.md/.txt)',
    pwd_ph: 'Κωδικός ασφαλείας',
    btn_upload: 'Ανέβασμα',
    poems_label: 'Ποιήματα',
    msg_pwd_err: 'Λάθος κωδικός.',
    msg_upload_err: 'Σφάλμα μεταφόρτωσης.',
    msg_ext_err: 'Επιτρέπονται μόνο αρχεία .md ή .txt.',
    msg_size_err: 'Το αρχείο είναι πολύ μεγάλο.',
    msg_success: 'Το ποίημα ανέβηκε επιτυχώς.',
    msg_move_err: 'Αποτυχία αποθήκευσης αρχείου.',
    msg_del_success: 'Το ποίημα διαγράφηκε.',
    msg_del_err: 'Αποτυχία διαγραφής.',
    welcome_msg: 'Καλωσήρθατε. Παρακαλώ ανεβάστε περιεχομενo για την αρχική σελίδα.',
    sort: 'Ταξινόμηση',
    sort_alpha: 'Α-Ω',
    sort_latest: 'Νεότερα',
    align: 'Στοίχιση',
    align_left: 'Αριστερά',
    align_center: 'Κέντρο',
    align_right: 'Δεξιά',
  },
  en: {
    lang_toggle: 'Ελληνικά',
    collections_title: 'Collections',
    no_collections: 'No published collections yet.',
    no_poems: 'No poems found. Upload or write one',
    add_collection: 'New Collection',
    author_name_label: 'Poet Name',
    collection_title_label: 'Collection Title',
    btn_create: 'Create',
    btn_cancel: 'Cancel',
    btn_login: 'Login',
    btn_save: 'Save',
    pwd_label: 'Password',
    management: 'Management',
    exit: 'Exit',
    theme_dark: 'Dark',
    theme_light: 'Light',
    msg_created: 'Collection created.',
    msg_error: 'Error.',
    msg_saved: 'All changes were saved.',
    msg_deleted: 'Collection deleted.',
    msg_del_error: 'Error deleting collection.',
    about_link: 'About Stanza',
    home: 'Home',
    profile: 'Profile',
    contents: 'Index',
    edit_profile: 'Edit Profile',
    edit_collection: 'Edit Collection',
    bio_label: 'Bio',
    photo_label: 'Photo',
    delete_collection: 'Delete',
    confirm_delete: 'Are you sure you want to delete this collection?',
    choose_file: 'Choose file',
    select_collection: 'Select collection',
    new_password_label: 'New Password',
    confirm_password_label: 'Confirm Password',
    msg_pwd_mismatch: 'Passwords do not match.',
    poet_name: 'Poet Name',
    site_title: 'Collection Title',
    archive: 'Collection',
    delete_inline: '(delete)',
    confirm_delete_poem: 'Are you sure you want to delete this poem?',
    back_to_archive: '← Back to Collection',
    view_poems: 'View Poems',
    add_poem: 'Add Poem',
    delete_poems: 'Delete Poems',
    upload_title: 'Upload new poem',
    file_label: 'Choose file (.md/.txt)',
    pwd_ph: 'Security code',
    btn_upload: 'Upload',
    poems_label: 'Poems',
    msg_pwd_err: 'Incorrect password.',
    msg_upload_err: 'File upload error.',
    msg_ext_err: 'Only .md or .txt files are allowed.',
    msg_size_err: 'File too large.',
    msg_success: 'Poem uploaded successfully.',
    msg_move_err: 'Failed to move uploaded file.',
    msg_del_success: 'Poem deleted.',
    msg_del_err: 'Failed to delete poem.',
    welcome_msg: 'Welcome. Please upload the landing page content.',
    sort: 'Sort',
    sort_alpha: 'A-Z',
    sort_latest: 'Latest',
    align: 'Alignment',
    align_left: 'Left',
    align_center: 'Center',
    align_right: 'Right',
  },
};

export function t(lang: Lang) {
  return (key: UIKey): string => ui[lang][key];
}

export function getLangFromUrl(url: URL): Lang {
  const [, lang] = url.pathname.split('/');
  if (lang === 'el' || lang === 'en') return lang;
  return 'en';
}

export function getAlternateLang(lang: Lang): Lang {
  return lang === 'en' ? 'el' : 'en';
}
