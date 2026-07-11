<?php
function getSupportedLanguages() {
    return [
        'en' => 'English',
        'es' => 'Español',
        'fr' => 'Français',
    ];
}

function getCurrentLanguage() {
    $lang = $_GET['lang'] ?? ($_COOKIE['eventix_lang'] ?? 'en');
    $supported = array_keys(getSupportedLanguages());
    if (!in_array($lang, $supported, true)) {
        $lang = 'en';
    }

    if (!headers_sent()) {
        setcookie('eventix_lang', $lang, time() + 60 * 60 * 24 * 30, '/');
    }

    return $lang;
}

function translate($key, $lang = 'en') {
    $strings = [
        'en' => [
            'explore_venues' => 'Explore Venues',
            'venues' => 'Venues',
            'my_bookings' => 'My Bookings',
            'my_reviews' => 'My Reviews',
            'dashboard' => 'Dashboard',
            'my_venues' => 'My Venues',
            'bookings' => 'Bookings',
            'earnings' => 'Earnings',
            'users' => 'Users',
            'payments' => 'Payments',
            'login' => 'Log in',
            'sign_up' => 'Sign up',
            'logout' => 'Log out',
            'discover_book' => 'Discover & Book',
            'hero_title' => 'Eventix Venues',
            'hero_subtitle' => 'Extraordinary spaces for every occasion — from intimate gatherings to grand celebrations.',
            'search_prompt' => 'What kind of venue are you looking for?',
            'popular_header' => '🔥 Most Popular',
            'popular_subtitle' => 'Our most booked and highly-rated spaces',
            'best_picks' => 'Our Best Picks',
            'best_picks_subtitle' => 'Handpicked for your next unforgettable event',
            'search' => 'Search',
            'reviews' => 'Reviews',
            'avg_rating' => 'Avg Rating',
            'write_first_review' => 'Write your first review',
            'no_reviews_yet' => 'No reviews yet',
            'leave_the_first_review' => 'Be the first to share your experience.',
            'my_reviews_subtitle' => 'View, edit, or remove the feedback you’ve left for venues.',
            'wedding' => 'Wedding',
            'corporate' => 'Corporate',
            'outdoor' => 'Outdoor',
            'party' => 'Party',
            'studio' => 'Studio',
        ],
        'es' => [
            'explore_venues' => 'Explorar lugares',
            'venues' => 'Lugares',
            'my_bookings' => 'Mis reservas',
            'my_reviews' => 'Mis reseñas',
            'dashboard' => 'Panel',
            'my_venues' => 'Mis lugares',
            'bookings' => 'Reservas',
            'earnings' => 'Ingresos',
            'users' => 'Usuarios',
            'payments' => 'Pagos',
            'login' => 'Iniciar sesión',
            'sign_up' => 'Regístrate',
            'logout' => 'Cerrar sesión',
            'discover_book' => 'Descubre y reserva',
            'hero_title' => 'Eventix Lugares',
            'hero_subtitle' => 'Espacios extraordinarios para cada ocasión — desde reuniones íntimas hasta grandes celebraciones.',
            'search_prompt' => '¿Qué tipo de lugar buscas?',
            'popular_header' => '🔥 Más populares',
            'popular_subtitle' => 'Nuestros espacios más reservados y mejor valorados',
            'best_picks' => 'Nuestros mejores consejos',
            'best_picks_subtitle' => 'Seleccionados para tu próximo evento inolvidable',
            'wedding' => 'Boda',
            'corporate' => 'Corporativo',
            'outdoor' => 'Exterior',
            'party' => 'Fiesta',
            'studio' => 'Estudio',
            'search' => 'Buscar',
            'reviews' => 'Reseñas',
            'avg_rating' => 'Calificación media',
            'write_first_review' => 'Escribe tu primera reseña',
            'no_reviews_yet' => 'Aún no hay reseñas',
            'leave_the_first_review' => 'Sé el primero en compartir tu experiencia.',
            'my_reviews_subtitle' => 'Ver, editar o eliminar los comentarios que has dejado para los lugares.',
            'wedding' => 'Boda',
            'corporate' => 'Corporativo',
            'outdoor' => 'Exterior',
            'party' => 'Fiesta',
            'studio' => 'Estudio',
        ],
        'fr' => [
            'explore_venues' => 'Explorer',
            'venues' => 'Lieux',
            'my_bookings' => 'Mes réservations',
            'my_reviews' => 'Mes avis',
            'dashboard' => 'Tableau de bord',
            'my_venues' => 'Mes lieux',
            'bookings' => 'Réservations',
            'earnings' => 'Revenus',
            'users' => 'Utilisateurs',
            'payments' => 'Paiements',
            'login' => 'Connexion',
            'sign_up' => 'S’inscrire',
            'logout' => 'Déconnexion',
            'discover_book' => 'Découvrir et réserver',
            'hero_title' => 'Eventix Lieux',
            'hero_subtitle' => 'Des espaces extraordinaires pour chaque occasion — des réunions intimistes aux grandes célébrations.',
            'search_prompt' => 'Quel type de lieu cherchez-vous ?',
            'popular_header' => '🔥 Les plus populaires',
            'popular_subtitle' => 'Nos espaces les plus réservés et les mieux notés',
            'best_picks' => 'Nos meilleurs choix',
            'best_picks_subtitle' => 'Sélectionnés pour votre prochain événement inoubliable',
            'search' => 'Rechercher',
            'reviews' => 'Avis',
            'avg_rating' => 'Note moyenne',
            'write_first_review' => 'Rédigez votre premier avis',
            'no_reviews_yet' => 'Pas encore d’avis',
            'leave_the_first_review' => 'Soyez le premier à partager votre expérience.',
            'my_reviews_subtitle' => 'Consultez, modifiez ou supprimez les avis que vous avez laissés pour les lieux.',
            'wedding' => 'Mariage',
            'corporate' => 'Professionnel',
            'outdoor' => 'Extérieur',
            'party' => 'Fête',
            'studio' => 'Studio',
        ],
    ];

    return $strings[$lang][$key] ?? $strings['en'][$key] ?? $key;
}
