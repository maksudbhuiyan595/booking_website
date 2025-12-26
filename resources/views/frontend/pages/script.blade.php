
{{-- Schema Markup --}}
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "TaxiService",
  "name": "Boston Logan Airport Taxi",
  "image": "{{ asset('images/Boston Logan Airport.webp') }}",
  "url": "{{ url('/') }}",
  "telephone": "+1857-331-9544",
  "email": "blairporttaxicab@gmail.com",
  "priceRange": "$$",
  "address": {
    "@type": "PostalAddress",
    "streetAddress": "3 Putnam Gardens Apt 22",
    "addressLocality": "Cambridge",
    "addressRegion": "MA",
    "postalCode": "02139",
    "addressCountry": "US"
  },
  "areaServed": [
    {"@type": "City", "name": "Boston"},
    {"@type": "City", "name": "Cambridge"},
    {"@type": "City", "name": "Somerville"},
    {"@type": "Airport", "name": "Boston Logan International Airport"}
  ],
  "openingHoursSpecification": {
    "@type": "OpeningHoursSpecification",
    "dayOfWeek": ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"],
    "opens": "00:00",
    "closes": "23:59"
  },
  "paymentAccepted": "Cash, Credit Card",
  "sameAs": [
    "https://www.facebook.com/bostonloganairporttaxi1",
    "https://twitter.com/blairporttaxi"
  ]
}
</script>
