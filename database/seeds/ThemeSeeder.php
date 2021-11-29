<?php

use App\Models\ThemeComponent;
use App\Models\ThemeContent;
use Illuminate\Database\Seeder;

class ThemeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // theme_page_id => [ key, title, status ]
        $themeComponents = [
            "1" => [
                ['hero', 'Make Learning Awesome!'],
                ['fizzyfest_work', 'How Does Fizzyfest Work?','How It\'s Work?'],
                ['fizzyfest_at'],
                ['client_say', 'What User Say About Us',' About Us'],
                ['signup_card']
            ]
        ];

        $this->setThemeComponents($themeComponents);

        // component_key => [ title, sub_title, description, image_url, link_url ]
        $themeContents = [
            "hero" => [
                [
                    'title' => 'Make learning awesome!',
                    'description' => 'Fizzyfest delivers engaging learning to billions.Teachers, students, businesses and parents all use Fizzyest for group learning, e-learning, distance learning, and self-study everywhere!',
                    'image_url' => 'hero-img.png',
                    'link_url' => '/app/login'
                ]
            ],
            "fizzyfest_work" => [
                [
                    'title' => 'Create',
                    'description' => 'It only takes minutes to create a learning game or trivia quiz on any topic, in any language.',
                    'image_url' => 'flaticon-pencil text-info'
                ],
                [
                    'title' => 'Host and Share',
                    'description' => 'Host a live game with questions on a big screen or share a game with remote players.',
                    'image_url' => 'flaticon-resource text-warning'
                ],
                [
                    'title' => 'Play',
                    'description' => 'Game on! Join a kahoot with a PIN provided by the host and answer questions on your device.',
                    'image_url' => 'flaticon-elearning text-success'
                ]
            ],
            'fizzyfest_at' => [
                [
                    'title' => 'Fizzyfest At Schools',
                    'description' => 'Make learning fun, engaging, and impactful for students studying at home and in school. Create fun learning games in minutes, or choose from millions of existing games to introduce a topic, review and reinforce knowledge, and run formative assessment.                    ',
                    'image_url' => 'fz-img-01.png',
                    'link_url' => '/schools'
                ],
                [
                    'title' => 'Fizzyfest At Work',
                    'description' => 'Millions in the workplace learn with Kahoot! From e-learning, to interactive presentations, training and virtual events, you can make an audience learn without even realizing it. Start using Kahoot! today with Microsoft Teams, Google Hangouts, Zoom, Slack and more!',
                    'image_url' => 'fz-img-03.png',
                    'link_url' => '/work'
                ],
                [
                    'title' => 'Fizzyfest At Home',
                    'description' => 'Give your kids engaging and easy-to-use learning games to study at home while school is out, or as an extra to school curriculum. Sign up and get access to millions of free learning games for the entire family.',
                    'image_url' => 'fz-img-02.png',
                    'link_url' => '/home'
                ]
            ],
            'client_say' => [
                [
                    'title' => 'Kishan Kevadiya',
                    'sub_title' => 'Facebook',
                    'description' => '(With Fizzyfest) people pay more attention to the training, and that is the dream of any trainer!',
                    'image_url'=> 'user_1.png'
                ],
                [
                    'title' => 'Hardik Mandaviya',
                    'sub_title' => '5th grade teacher',
                    'description' => 'Introducing a topic, learning a concept, practicing a skill, or reviewing a lesson, while having fun, Fizzyfest can do it all!',
                    'image_url' => 'user_2.png'
                ]
            ],
            'signup_card' => [
                [
                    'title' => 'Signup and Join Us.',
                    'description' => 'Fizzyfest delivers engaging learning to billions. Here, Join us.',
                    'link_url' => '/app/register',
                    'image_url' => 'have.jpg'
                ]
            ]
        ];

        $this->setThemeContent($themeContents);
        return;
    }

    private function setThemeComponents($themeComponents)
    {
        foreach ($themeComponents as $key => $themeComponent) {
            foreach ($themeComponent as $value) {
                ThemeComponent::create([
                    'theme_page' => $key,
                    'key' => $value[0],
                    'title' => $value[1] ?? null,
                    'sub_title' => $value[2] ?? null,
                    'status' => $value[3] ?? true
                ]);
            }
        }
    }

    private function setThemeContent($themeContents)
    {
        foreach ($themeContents as $key => $themeContent) {
            $componentId = ThemeComponent::where('key', $key)->pluck('id')[0];

            foreach ($themeContent as $key => $content) {
                ThemeContent::create([
                    'theme_component_id' => $componentId,
                    'title' => $content['title'] ?? null,
                    'sub_title' => $content['sub_title'] ?? null,
                    'description' => $content['description'] ?? null,
                    'image_url' => $content['image_url'] ?? null,
                    'link_url' => $content['link_url'] ?? null
                ]);
            }
        }
    }
}
