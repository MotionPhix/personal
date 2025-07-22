<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PortfolioController extends Controller
{
  /**
   * Display the portfolio landing page.
   */
  public function index(): Response
  {
    return Inertia::render('Index', [
      'personal' => $this->getPersonalInfo(),
      'about' => $this->getAboutInfo(),
      'skills' => $this->getSkills(),
      'services' => $this->getServices(),
      'projects' => $this->getProjects(),
      'contact' => $this->getContactInfo(),
      'socials' => $this->getSocialLinks(),
      'meta' => $this->getMetaData(),
    ]);
  }

  /**
   * Get personal information
   */
  private function getPersonalInfo(): array
  {
    return [
      'name' => 'Your Full Name',
      'firstName' => 'Your',
      'lastName' => 'Name',
      'initials' => 'YN',
      'greeting' => 'Hello, I\'m',
      'tagline' => 'Creative Designer & Developer',
      'bio' => 'Crafting digital experiences with over a decade of expertise in design and development, bringing creative visions to life from the heart of Lilongwe, Malawi.',
      'location' => 'Lilongwe, Malawi',
      'experience' => '10+ Years',
      'availability' => 'Available for Projects',
      'avatar' => null, // Set to asset('images/avatar.jpg') if you have an avatar
      'primarySkills' => [
        'Graphic Design',
        'Web Development',
        'UI/UX Design',
        'Brand Identity'
      ],
    ];
  }

  /**
   * Get about section information
   */
  private function getAboutInfo(): array
  {
    return [
      'introduction' => 'Based in the vibrant heart of Lilongwe, Malawi, I combine creative design with technical expertise to deliver exceptional digital experiences. My passion lies in transforming ideas into compelling visual narratives and functional digital solutions.',
      'mission' => 'To empower businesses and individuals through thoughtful design and innovative technology solutions that make a real difference in people\'s lives.',
      'philosophy' => 'I believe that great design is not just about how something looks, but how it works and how it makes people feel.',
    ];
  }

  /**
   * Get skills with proficiency levels
   */
  private function getSkills(): array
  {
    return [
      [
        'name' => 'Graphic Design',
        'level' => 95,
        'description' => 'Adobe Creative Suite, Brand Identity, Print Design'
      ],
      [
        'name' => 'Web Development',
        'level' => 90,
        'description' => 'Laravel, Vue.js, React, PHP, JavaScript'
      ],
      [
        'name' => 'UI/UX Design',
        'level' => 88,
        'description' => 'Figma, Adobe XD, Prototyping, User Research'
      ],
      [
        'name' => 'Brand Identity',
        'level' => 92,
        'description' => 'Logo Design, Brand Guidelines, Visual Identity'
      ],
      [
        'name' => 'Mobile Design',
        'level' => 85,
        'description' => 'Responsive Design, Mobile-First Approach'
      ],
      [
        'name' => 'Digital Strategy',
        'level' => 83,
        'description' => 'SEO, Analytics, Performance Optimization'
      ]
    ];
  }

  /**
   * Get services offered
   */
  private function getServices(): array
  {
    return [
      [
        'title' => 'Graphic Design',
        'description' => 'Creating stunning visual identities, logos, and brand materials that make lasting impressions and communicate your brand\'s essence effectively.',
        'icon' => 'palette',
        'features' => [
          'Logo Design',
          'Brand Identity',
          'Print Design',
          'Marketing Materials'
        ],
        'price_range' => '$500 - $2,000'
      ],
      [
        'title' => 'Web Development',
        'description' => 'Building modern, responsive websites and web applications using cutting-edge technologies that deliver exceptional user experiences.',
        'icon' => 'code',
        'features' => [
          'Custom Web Applications',
          'E-commerce Solutions',
          'CMS Development',
          'API Integration'
        ],
        'price_range' => '$1,000 - $10,000'
      ],
      [
        'title' => 'Mobile Design',
        'description' => 'Designing intuitive mobile experiences that engage users, drive conversions, and work seamlessly across all devices.',
        'icon' => 'smartphone',
        'features' => [
          'Mobile App UI/UX',
          'Responsive Design',
          'Progressive Web Apps',
          'Mobile Optimization'
        ],
        'price_range' => '$800 - $5,000'
      ],
      [
        'title' => 'UI/UX Design',
        'description' => 'Crafting user-centered designs that balance aesthetics with functionality, ensuring every interaction is meaningful and intuitive.',
        'icon' => 'monitor',
        'features' => [
          'User Research',
          'Wireframing',
          'Prototyping',
          'Usability Testing'
        ],
        'price_range' => '$1,200 - $6,000'
      ],
      [
        'title' => 'Brand Identity',
        'description' => 'Developing comprehensive brand strategies and visual identity systems that tell your story and connect with your audience.',
        'icon' => 'pen-tool',
        'features' => [
          'Brand Strategy',
          'Visual Identity',
          'Brand Guidelines',
          'Brand Collateral'
        ],
        'price_range' => '$1,500 - $8,000'
      ],
      [
        'title' => 'Digital Strategy',
        'description' => 'Strategic planning for digital transformation and online presence optimization to maximize your digital impact.',
        'icon' => 'zap',
        'features' => [
          'SEO Strategy',
          'Content Strategy',
          'Analytics Setup',
          'Performance Optimization'
        ],
        'price_range' => '$800 - $3,000'
      ]
    ];
  }

  /**
   * Get recent projects/portfolio
   */
  private function getProjects(): array
  {
    return [
      [
        'title' => 'Malawi Tourism Board - Digital Platform',
        'description' => 'A comprehensive digital platform showcasing Malawi\'s tourism attractions with interactive maps, booking systems, and multilingual support.',
        'technologies' => ['Laravel', 'Vue.js', 'MySQL', 'Stripe', 'Google Maps API'],
        'category' => 'Web Development',
        'year' => '2024',
        'client' => 'Malawi Tourism Board',
        'image' => null, // Set to asset('images/projects/tourism.jpg') if available
        'url' => null,
        'status' => 'Completed'
      ],
      [
        'title' => 'Lilongwe Bank - Brand Identity',
        'description' => 'Complete brand identity redesign for a local financial institution, including logo, brand guidelines, and marketing collateral.',
        'technologies' => ['Adobe Illustrator', 'Photoshop', 'InDesign', 'Brand Strategy'],
        'category' => 'Brand Design',
        'year' => '2024',
        'client' => 'Lilongwe Bank',
        'image' => null,
        'url' => null,
        'status' => 'Completed'
      ],
      [
        'title' => 'MalawiTech Startup - Mobile App',
        'description' => 'User-friendly mobile application for fintech startup with biometric authentication, real-time notifications, and seamless UX.',
        'technologies' => ['React Native', 'Node.js', 'MongoDB', 'JWT', 'Firebase'],
        'category' => 'Mobile Development',
        'year' => '2024',
        'client' => 'MalawiTech Startup',
        'image' => null,
        'url' => null,
        'status' => 'In Development'
      ],
      [
        'title' => 'Chanco University - Campus Portal',
        'description' => 'Modern student portal with course management, grade tracking, and communication tools for Chancellor College.',
        'technologies' => ['Laravel', 'Vue.js', 'MySQL', 'Redis', 'WebSockets'],
        'category' => 'Web Application',
        'year' => '2023',
        'client' => 'Chancellor College',
        'image' => null,
        'url' => null,
        'status' => 'Completed'
      ],
      [
        'title' => 'Malawi Coffee Export - E-commerce',
        'description' => 'E-commerce platform for coffee exporters with inventory management, international shipping, and payment processing.',
        'technologies' => ['WooCommerce', 'PHP', 'MySQL', 'PayPal', 'DHL API'],
        'category' => 'E-commerce',
        'year' => '2023',
        'client' => 'Malawi Coffee Exporters',
        'image' => null,
        'url' => null,
        'status' => 'Completed'
      ],
      [
        'title' => 'NGO Health Initiative - Website',
        'description' => 'Professional website for health NGO with donation system, volunteer management, and multilingual support.',
        'technologies' => ['WordPress', 'PHP', 'JavaScript', 'SCSS', 'Stripe'],
        'category' => 'Website',
        'year' => '2023',
        'client' => 'Health for All NGO',
        'image' => null,
        'url' => null,
        'status' => 'Completed'
      ]
    ];
  }

  /**
   * Get contact information
   */
  private function getContactInfo(): array
  {
    return [
      'email' => 'your.email@example.com',
      'phone' => '+265 XXX XXX XXX',
      'website' => 'www.yourwebsite.com',
      'address' => [
        'street' => 'Your Street Address',
        'city' => 'Lilongwe',
        'area' => 'Area 10', // Common area designation in Lilongwe
        'country' => 'Malawi',
        'postal_code' => null
      ],
      'business_hours' => [
        'monday_friday' => '8:00 AM - 6:00 PM',
        'saturday' => '9:00 AM - 2:00 PM',
        'sunday' => 'Closed',
        'timezone' => 'CAT (UTC+2)'
      ],
      'response_time' => 'Within 24 hours',
      'languages' => ['English', 'Chichewa']
    ];
  }

  /**
   * Get social media links
   */
  private function getSocialLinks(): array
  {
    return [
      [
        'name' => 'GitHub',
        'url' => 'https://github.com/yourusername',
        'icon' => 'github',
        'username' => '@yourusername'
      ],
      [
        'name' => 'LinkedIn',
        'url' => 'https://linkedin.com/in/yourprofile',
        'icon' => 'linkedin',
        'username' => 'Your Name'
      ],
      [
        'name' => 'Twitter',
        'url' => 'https://twitter.com/yourusername',
        'icon' => 'twitter',
        'username' => '@yourusername'
      ],
      [
        'name' => 'Instagram',
        'url' => 'https://instagram.com/yourusername',
        'icon' => 'instagram',
        'username' => '@yourusername'
      ],
      [
        'name' => 'Dribbble',
        'url' => 'https://dribbble.com/yourusername',
        'icon' => 'dribbble',
        'username' => 'Your Name'
      ],
      [
        'name' => 'Behance',
        'url' => 'https://behance.net/yourusername',
        'icon' => 'behance',
        'username' => 'Your Name'
      ]
    ];
  }

  /**
   * Get meta data for SEO
   */
  private function getMetaData(): array
  {
    return [
      'title' => 'Your Name - Creative Designer & Developer | Lilongwe, Malawi',
      'description' => 'Professional graphic designer and web developer based in Lilongwe, Malawi. Specializing in brand identity, web development, and digital solutions with 10+ years of experience.',
      'keywords' => [
        'graphic designer malawi',
        'web developer lilongwe',
        'brand identity malawi',
        'web development malawi',
        'ui ux designer malawi',
        'freelance designer malawi',
        'digital marketing malawi'
      ],
      'og_image' => null, // Set to asset('images/og-image.jpg') if available
      'canonical_url' => url('/'),
      'author' => 'Your Name',
      'locale' => 'en_MW', // English - Malawi
      'type' => 'website'
    ];
  }

  /**
   * Handle contact form submission
   */
  public function contact(Request $request)
  {
    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'email' => 'required|email|max:255',
      'subject' => 'nullable|string|max:255',
      'message' => 'required|string|max:5000',
      'phone' => 'nullable|string|max:20',
    ]);

    // TODO: Implement your preferred notification method
    // Options: Mail, Database, Queue job, Third-party service, etc.

    // Example: Send email notification
    // Mail::to('your.email@example.com')->send(new ContactFormMail($validated));

    // Example: Store in database
    // ContactMessage::create($validated);

    // Example: Send to Slack/Discord
    // Notification::route('slack', env('SLACK_WEBHOOK_URL'))->notify(new ContactFormNotification($validated));

    return response()->json([
      'message' => 'Thank you for your message! I\'ll get back to you within 24 hours.',
      'success' => true
    ]);
  }

  /**
   * Download CV/Resume
   */
  public function downloadCV()
  {
    $filePath = storage_path('app/public/cv/your-cv.pdf');

    if (!file_exists($filePath)) {
      abort(404, 'CV not found');
    }

    return response()->download($filePath, 'YourName-CV-2024.pdf');
  }
}
