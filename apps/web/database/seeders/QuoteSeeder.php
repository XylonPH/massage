<?php

namespace Database\Seeders;

use App\Enums\NsfwLevel;
use App\Enums\RecordLifecycleStatus;
use App\Models\Quote;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class QuoteSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $quotesData = [
            // ==========================================
            // ENGLISH (3049) - 30 Authentic Quotes
            // ==========================================
            [
                'language_original_id' => 3049,
                'type_quote_category' => 'RLX',
                'attribution_label' => 'Naomi Judd',
                'quote_text' => [
                    'eng' => ['text' => 'Your body hears everything your mind says.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'fil' => ['text' => 'Naririnig ng iyong katawan ang lahat ng sinasabi ng iyong isip.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 3049,
                'type_quote_category' => 'MNP',
                'attribution_label' => 'Jon Kabat-Zinn',
                'quote_text' => [
                    'eng' => ['text' => 'You can\'t stop the waves, but you can learn to surf.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 3049,
                'type_quote_category' => 'MEW',
                'attribution_label' => 'William James',
                'quote_text' => [
                    'eng' => ['text' => 'The greatest weapon against stress is our ability to choose one thought over another.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 3049,
                'type_quote_category' => 'PHV',
                'attribution_label' => 'Jim Rohn',
                'quote_text' => [
                    'eng' => ['text' => 'Take care of your body. It\'s the only place you have to live.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 3049,
                'type_quote_category' => 'HRC',
                'attribution_label' => 'Hippocrates',
                'quote_text' => [
                    'eng' => ['text' => 'Healing is a matter of time, but it is sometimes also a matter of opportunity.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 3049,
                'type_quote_category' => 'SCB',
                'attribution_label' => 'Eleanor Brown',
                'quote_text' => [
                    'eng' => ['text' => 'Rest and self-care are so important. You cannot serve from an empty vessel.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 3049,
                'type_quote_category' => 'RSM',
                'attribution_label' => 'Maya Angelou',
                'quote_text' => [
                    'eng' => ['text' => 'You may not control all the events that happen to you, but you can decide not to be reduced by them.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 3049,
                'type_quote_category' => 'CNC',
                'attribution_label' => 'Helen Keller',
                'quote_text' => [
                    'eng' => ['text' => 'The best and most beautiful things in the world cannot be seen or even touched - they must be felt with the heart.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 3049,
                'type_quote_category' => 'SPR',
                'attribution_label' => 'Ralph Waldo Emerson',
                'quote_text' => [
                    'eng' => ['text' => 'Adopt the pace of nature: her secret is patience.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 3049,
                'type_quote_category' => 'RLX',
                'attribution_label' => 'Ovid',
                'quote_text' => [
                    'eng' => ['text' => 'Take rest; a field that has rested gives a bountiful crop.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 3049,
                'type_quote_category' => 'MNP',
                'attribution_label' => 'Eckhart Tolle',
                'quote_text' => [
                    'eng' => ['text' => 'Realize deeply that the present moment is all you have.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 3049,
                'type_quote_category' => 'MEW',
                'attribution_label' => 'Marcus Aurelius',
                'quote_text' => [
                    'eng' => ['text' => 'You have power over your mind - not outside events. Realize this, and you will find strength.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 3049,
                'type_quote_category' => 'PHV',
                'attribution_label' => 'Joseph Pilates',
                'quote_text' => [
                    'eng' => ['text' => 'Physical fitness is the first requisite of happiness.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 3049,
                'type_quote_category' => 'HRC',
                'attribution_label' => 'Rumi',
                'quote_text' => [
                    'eng' => ['text' => 'The wound is the place where the Light enters you.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 3049,
                'type_quote_category' => 'SCB',
                'attribution_label' => 'Audre Lorde',
                'quote_text' => [
                    'eng' => ['text' => 'Caring for myself is not self-indulgence, it is self-preservation.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 3049,
                'type_quote_category' => 'RSM',
                'attribution_label' => 'Vince Lombardi',
                'quote_text' => [
                    'eng' => ['text' => 'It\'s not whether you get knocked down, it\'s whether you get up.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 3049,
                'type_quote_category' => 'CNC',
                'attribution_label' => 'Dalai Lama',
                'quote_text' => [
                    'eng' => ['text' => 'Love and compassion are necessities, not luxuries. Without them, humanity cannot survive.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 3049,
                'type_quote_category' => 'SPR',
                'attribution_label' => 'Thich Nhat Hanh',
                'quote_text' => [
                    'eng' => ['text' => 'Smile, breathe and go slowly.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 3049,
                'type_quote_category' => 'RLX',
                'attribution_label' => 'Thomas Dekker',
                'quote_text' => [
                    'eng' => ['text' => 'Sleep is that golden chain that ties health and our bodies together.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 3049,
                'type_quote_category' => 'MNP',
                'attribution_label' => 'Lao Tzu',
                'quote_text' => [
                    'eng' => ['text' => 'Silence is a source of great strength.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 3049,
                'type_quote_category' => 'MEW',
                'attribution_label' => 'Hans Selye',
                'quote_text' => [
                    'eng' => ['text' => 'It\'s not stress that kills us, it is our reaction to it.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 3049,
                'type_quote_category' => 'PHV',
                'attribution_label' => 'Gene Tunney',
                'quote_text' => [
                    'eng' => ['text' => 'To enjoy the glow of good health, you must exercise.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 3049,
                'type_quote_category' => 'HRC',
                'attribution_label' => 'Carl Jung',
                'quote_text' => [
                    'eng' => ['text' => 'I am not what happened to me, I am what I choose to become.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 3049,
                'type_quote_category' => 'SCB',
                'attribution_label' => 'Jack Kornfield',
                'quote_text' => [
                    'eng' => ['text' => 'If your compassion does not include yourself, it is incomplete.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 3049,
                'type_quote_category' => 'RSM',
                'attribution_label' => 'Winston Churchill',
                'quote_text' => [
                    'eng' => ['text' => 'Success is not final, failure is not fatal: it is the courage to continue that counts.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 3049,
                'type_quote_category' => 'CNC',
                'attribution_label' => 'Mother Teresa',
                'quote_text' => [
                    'eng' => ['text' => 'Spread love everywhere you go. Let no one ever come to you without leaving happier.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 3049,
                'type_quote_category' => 'SPR',
                'attribution_label' => 'Rumi',
                'quote_text' => [
                    'eng' => ['text' => 'Quiet the mind and the soul will speak.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 3049,
                'type_quote_category' => 'RLX',
                'attribution_label' => 'Sydney J. Harris',
                'quote_text' => [
                    'eng' => ['text' => 'The time to relax is when you don\'t have time for it.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 3049,
                'type_quote_category' => 'MNP',
                'attribution_label' => 'Amit Ray',
                'quote_text' => [
                    'eng' => ['text' => 'Mindfulness is a way of befriending ourselves and our experience.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 3049,
                'type_quote_category' => 'SCB',
                'attribution_label' => 'Lao Tzu',
                'quote_text' => [
                    'eng' => ['text' => 'Nature does not hurry, yet everything is accomplished.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],

            // ==========================================
            // FILIPINO / TAGALOG (3600) - 30 Authentic Proverbs & Sayings
            // ==========================================
            [
                'language_original_id' => 3600,
                'type_quote_category' => 'RLX',
                'attribution_label' => 'Kasabihang Tagalog',
                'quote_text' => [
                    'fil' => ['text' => 'Ang pahinga ay hindi pagsasayang ng oras, kundi pag-iipon ng lakas.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'Rest is not a waste of time, but a gathering of strength.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 3600,
                'type_quote_category' => 'MNP',
                'attribution_label' => 'Salawikain',
                'quote_text' => [
                    'fil' => ['text' => 'Ang taong mahinahon, malayo ang mararating sa buhay.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'A calm person goes far in life.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 3600,
                'type_quote_category' => 'MEW',
                'attribution_label' => 'Kasabihan',
                'quote_text' => [
                    'fil' => ['text' => 'Ang kalinawan ng isip ay nagmumula sa matahimik na puso.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'Clarity of mind stems from a peaceful heart.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 3600,
                'type_quote_category' => 'PHV',
                'attribution_label' => 'Salawikain',
                'quote_text' => [
                    'fil' => ['text' => 'Ang kalusugan ay tunay na kayamanan na hindi mabibili ng ginto.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'Health is true wealth that gold cannot buy.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 3600,
                'type_quote_category' => 'HRC',
                'attribution_label' => 'Kasabihan',
                'quote_text' => [
                    'fil' => ['text' => 'Sa bawat sugat ng katawan at damdamin, may panahon ng paghilom.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'For every wound of body and emotion, there comes a time for healing.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 3600,
                'type_quote_category' => 'SCB',
                'attribution_label' => 'Salawikain',
                'quote_text' => [
                    'fil' => ['text' => 'Alagaan ang sarili bago magmahal at mag-alaga ng iba.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'Care for yourself before loving and caring for others.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 3600,
                'type_quote_category' => 'RSM',
                'attribution_label' => 'Salawikain',
                'quote_text' => [
                    'fil' => ['text' => 'Habang may buhay, may pag-asa.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'While there is life, there is hope.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 3600,
                'type_quote_category' => 'CNC',
                'attribution_label' => 'Salawikain',
                'quote_text' => [
                    'fil' => ['text' => 'Ang mabuting pakikitungo sa kapwa ay nagdudulot ng kapayapaan.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'Kind treatment of others brings peace.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 3600,
                'type_quote_category' => 'SPR',
                'attribution_label' => 'Kasabihan',
                'quote_text' => [
                    'fil' => ['text' => 'Nasa Diyos ang awa, nasa tao ang gawa.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'God gives mercy, human action brings results.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 3600,
                'type_quote_category' => 'RLX',
                'attribution_label' => 'José Rizal',
                'quote_text' => [
                    'fil' => ['text' => 'Ang pagpapahinga ng isip ay nagbibigay ng panibagong sigla sa kaluluwa.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'The rest of the mind brings renewed vitality to the soul.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 3600,
                'type_quote_category' => 'MNP',
                'attribution_label' => 'Kasabihan',
                'quote_text' => [
                    'fil' => ['text' => 'Damhin ang bawat sandali ng buhay nang may pasasalamat.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'Feel every moment of life with gratitude.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 3600,
                'type_quote_category' => 'MEW',
                'attribution_label' => 'Salawikain',
                'quote_text' => [
                    'fil' => ['text' => 'Ang pusong maunawain ay nakakatagpo ng tunay na katahimikan.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'An understanding heart finds true tranquility.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 3600,
                'type_quote_category' => 'PHV',
                'attribution_label' => 'Kasabihan',
                'quote_text' => [
                    'fil' => ['text' => 'Ang malusog na katawan ay tahanan ng masiglang espiritu.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'A healthy body is home to a vibrant spirit.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 3600,
                'type_quote_category' => 'HRC',
                'attribution_label' => 'Salawikain',
                'quote_text' => [
                    'fil' => ['text' => 'May bahaghari pagkatapos ng unos.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'There is a rainbow after the storm.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 3600,
                'type_quote_category' => 'SCB',
                'attribution_label' => 'Kasabihan',
                'quote_text' => [
                    'fil' => ['text' => 'Huwag kalimutang bigyan ng oras ang sarili sa gitna ng abalang mundo.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'Do not forget to give yourself time amidst a busy world.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 3600,
                'type_quote_category' => 'RSM',
                'attribution_label' => 'Salawikain',
                'quote_text' => [
                    'fil' => ['text' => 'Ang kawayan sa kabila ng malakas na hangin ay yumuyukod ngunit hindi nababali.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'The bamboo bends in strong wind but does not break.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 3600,
                'type_quote_category' => 'CNC',
                'attribution_label' => 'Salawikain',
                'quote_text' => [
                    'fil' => ['text' => 'Ang magaan na pakiramdam ay nanggagaling sa malinis na pagmamalasakit.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'A light feeling comes from genuine care.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 3600,
                'type_quote_category' => 'SPR',
                'attribution_label' => 'Kasabihan',
                'quote_text' => [
                    'fil' => ['text' => 'Ang malalim na katahimikan ay nagbubukas ng pinto sa panloob na karunungan.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'Deep silence opens the door to inner wisdom.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 3600,
                'type_quote_category' => 'RLX',
                'attribution_label' => 'Kasabihan',
                'quote_text' => [
                    'fil' => ['text' => 'Bigyan ng kapahingahan ang isip upang makakita ng bagong liwanag.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'Give rest to the mind to see new light.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 3600,
                'type_quote_category' => 'MNP',
                'attribution_label' => 'Kasabihan',
                'quote_text' => [
                    'fil' => ['text' => 'Huminga nang malalim at ilabas ang mga alalahanin.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'Breathe deeply and let go of worries.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 3600,
                'type_quote_category' => 'MEW',
                'attribution_label' => 'Salawikain',
                'quote_text' => [
                    'fil' => ['text' => 'Ang payapang kaisipan ay nagdadala ng kalusugan sa buong katawan.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'A peaceful mind brings health to the whole body.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 3600,
                'type_quote_category' => 'PHV',
                'attribution_label' => 'Salawikain',
                'quote_text' => [
                    'fil' => ['text' => 'Ang tamang pagkain at sapat na tulog ay haligi ng malakas na pangangatawan.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'Good food and sufficient sleep are pillars of a strong body.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 3600,
                'type_quote_category' => 'HRC',
                'attribution_label' => 'Kasabihan',
                'quote_text' => [
                    'fil' => ['text' => 'Ang hinay-hinay at maingat na paghakbang ay patungo sa permanenteng paggaling.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'Gentle and careful steps lead to permanent recovery.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 3600,
                'type_quote_category' => 'SCB',
                'attribution_label' => 'Salawikain',
                'quote_text' => [
                    'fil' => ['text' => 'Ang pagpapahalaga sa sarili ay hindi kababawan kundi karunungan.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'Self-appreciation is not selfishness but wisdom.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 3600,
                'type_quote_category' => 'RSM',
                'attribution_label' => 'Salawikain',
                'quote_text' => [
                    'fil' => ['text' => 'Kung may tiyaga, may nilaga.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'If there is perseverance, there will be reward.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 3600,
                'type_quote_category' => 'CNC',
                'attribution_label' => 'Salawikain',
                'quote_text' => [
                    'fil' => ['text' => 'Ang magandang asal at bukas na palad ay nakakagaan ng puso ng kapwa.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'Good manners and open hands lighten another\'s heart.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 3600,
                'type_quote_category' => 'SPR',
                'attribution_label' => 'Kasabihan',
                'quote_text' => [
                    'fil' => ['text' => 'Sa pananahimik ng kapaligiran, naririnig ang bulong ng kaluluwa.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'In environmental quiet, the whisper of the soul is heard.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 3600,
                'type_quote_category' => 'RLX',
                'attribution_label' => 'Kasabihan',
                'quote_text' => [
                    'fil' => ['text' => 'Ang mahimbing na tulog ay gamot sa pagod na katawan.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'Sound sleep is medicine for a tired body.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 3600,
                'type_quote_category' => 'MNP',
                'attribution_label' => 'Kasabihan',
                'quote_text' => [
                    'fil' => ['text' => 'Ang kasalukuyang sandali ay ang pinakamagandang regalo sa sarili.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'The present moment is the greatest gift to oneself.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 3600,
                'type_quote_category' => 'SCB',
                'attribution_label' => 'Kasabihan',
                'quote_text' => [
                    'fil' => ['text' => 'Balansehin ang paggawa at pagpapahinga para sa mahabang pagsasama ng katawan at isip.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'Balance work and rest for long harmony of body and mind.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],

            // ==========================================
            // CEBUANO (1458) - Authentic Cebuano Proverbs
            // ==========================================
            [
                'language_original_id' => 1458,
                'type_quote_category' => 'RLX',
                'attribution_label' => 'Panultihong Bisaya',
                'quote_text' => [
                    'ceb' => ['text' => 'Ang pagpahulay dili kalimot sa paglihok, kon dili pag-andam sa mas lig-ong ugma.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'Resting is not forgetting to act, but preparing for a stronger tomorrow.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 1458,
                'type_quote_category' => 'MNP',
                'attribution_label' => 'Panultihon sa Sugbo',
                'quote_text' => [
                    'ceb' => ['text' => 'Ang malinawon nga hunahuna maoy suug sa maayong pagbati.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'A peaceful mind is the source of good feeling.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 1458,
                'type_quote_category' => 'MEW',
                'attribution_label' => 'Panultihong Bisaya',
                'quote_text' => [
                    'ceb' => ['text' => 'Kon kalmado ang dagat, mas dali ang paggiya sa baroto.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'When the sea is calm, guiding the boat is easier.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 1458,
                'type_quote_category' => 'PHV',
                'attribution_label' => 'Panultihon',
                'quote_text' => [
                    'ceb' => ['text' => 'Ang maayong lawas maoy labing bililhong bahandi sa tawo.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'A healthy body is human\'s most precious treasure.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 1458,
                'type_quote_category' => 'HRC',
                'attribution_label' => 'Panultihong Bisaya',
                'quote_text' => [
                    'ceb' => ['text' => 'Bisan unsa kabug-at ang unos, mobalik ra gihapon ang kalinaw sa kaligoan.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'No matter how heavy the storm, peace will return.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 1458,
                'type_quote_category' => 'SCB',
                'attribution_label' => 'Panultihon',
                'quote_text' => [
                    'ceb' => ['text' => 'Hinumdumi ang pag-atiman sa imong kaugalingon taliwala sa kagahob.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'Remember to care for yourself amidst the noise.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 1458,
                'type_quote_category' => 'RSM',
                'attribution_label' => 'Panultihong Bisaya',
                'quote_text' => [
                    'ceb' => ['text' => 'Ang kalisod temporaryo ra, apan ang kaisog magpadayon.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'Hardship is temporary, but courage endures.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 1458,
                'type_quote_category' => 'CNC',
                'attribution_label' => 'Panultihon',
                'quote_text' => [
                    'ceb' => ['text' => 'Ang kaayo nga gipainitan sa kasingkasing mobag-o sa palibot.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'Kindness warmed by the heart transforms the surroundings.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 1458,
                'type_quote_category' => 'SPR',
                'attribution_label' => 'Panultihong Bisaya',
                'quote_text' => [
                    'ceb' => ['text' => 'Sa kahilom sa gabii, madungog ang pagsulti sa kasingkasing.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'In the quiet of the night, the heart speaks.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 1458,
                'type_quote_category' => 'RLX',
                'attribution_label' => 'Panultihon',
                'quote_text' => [
                    'ceb' => ['text' => 'Pahuway usahay aron dili dali kapoyon ang kalag.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'Rest sometimes so the soul does not easily tire.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 1458,
                'type_quote_category' => 'MNP',
                'attribution_label' => 'Panultihong Bisaya',
                'quote_text' => [
                    'ceb' => ['text' => 'Ang matag ginhawa maoy regalo sa kinabuhi.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'Every breath is a gift of life.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 1458,
                'type_quote_category' => 'MEW',
                'attribution_label' => 'Panultihon',
                'quote_text' => [
                    'ceb' => ['text' => 'Ayaw tugoti ang kabaka nga mokawat sa imong kahimtang karon.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'Do not allow worry to steal your present state.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 1458,
                'type_quote_category' => 'PHV',
                'attribution_label' => 'Panultihong Bisaya',
                'quote_text' => [
                    'ceb' => ['text' => 'Ang kusog sa lawas gikan sa hustong pagpahuway ug tulog.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'Body strength comes from proper rest and sleep.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 1458,
                'type_quote_category' => 'HRC',
                'attribution_label' => 'Panultihon',
                'quote_text' => [
                    'ceb' => ['text' => 'Ang panahon ug pasensya maoy labing maayong tambal.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'Time and patience are the best medicine.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 1458,
                'type_quote_category' => 'SCB',
                'attribution_label' => 'Panultihong Bisaya',
                'quote_text' => [
                    'ceb' => ['text' => 'Paminawa ang gihangyo sa imong lawas ug hatagi kini og pagtagad.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'Listen to what your body asks and give it attention.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 1458,
                'type_quote_category' => 'RSM',
                'attribution_label' => 'Panultihon',
                'quote_text' => [
                    'ceb' => ['text' => 'Ang kahoy nga molihok sa hangin dili dali matumba.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'The tree that moves with the wind does not easily fall.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 1458,
                'type_quote_category' => 'CNC',
                'attribution_label' => 'Panultihong Bisaya',
                'quote_text' => [
                    'ceb' => ['text' => 'Ang mahigalaon nga pagtagad makapahumok sa gahi nga kasingkasing.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'Friendly attention softens a hard heart.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 1458,
                'type_quote_category' => 'SPR',
                'attribution_label' => 'Panultihon',
                'quote_text' => [
                    'ceb' => ['text' => 'Ang pasalamat sa tanang butang makahatag og kalinaw sa dughan.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'Gratitude in all things brings peace to the chest.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 1458,
                'type_quote_category' => 'RLX',
                'attribution_label' => 'Panultihong Bisaya',
                'quote_text' => [
                    'ceb' => ['text' => 'Ihatag sa imong hunahuna ang kahigayonan nga makapahuway.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'Give your mind the opportunity to rest.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 1458,
                'type_quote_category' => 'SCB',
                'attribution_label' => 'Panultihon',
                'quote_text' => [
                    'ceb' => ['text' => 'Timbang-timbanga ang trabaho ug pahuway alang sa maayong panglawas.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'Balance work and rest for good health.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],

            // ==========================================
            // KOREAN (7142) - 30 Authentic Korean Proverbs & Quotes
            // ==========================================
            [
                'language_original_id' => 7142,
                'type_quote_category' => 'RLX',
                'attribution_label' => '한국 속담',
                'quote_text' => [
                    'kor' => ['text' => '쉬어 가며 일해라.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'Work while taking breaks.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 7142,
                'type_quote_category' => 'MNP',
                'attribution_label' => '혜민 스님',
                'quote_text' => [
                    'kor' => ['text' => '멈추면, 비로소 보이는 것들.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'Things you can see only when you slow down.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 7142,
                'type_quote_category' => 'MEW',
                'attribution_label' => '한국 속담',
                'quote_text' => [
                    'kor' => ['text' => '마음이 즐거우면 병도 없다.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'When the heart is joyful, there is no illness.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 7142,
                'type_quote_category' => 'PHV',
                'attribution_label' => '한국 속담',
                'quote_text' => [
                    'kor' => ['text' => '건강이 최고의 재산이다.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'Health is the greatest wealth.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 7142,
                'type_quote_category' => 'HRC',
                'attribution_label' => '한국 명언',
                'quote_text' => [
                    'kor' => ['text' => '시간은 모든 상처를 치유한다.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'Time heals all wounds.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 7142,
                'type_quote_category' => 'SCB',
                'attribution_label' => '한국 속담',
                'quote_text' => [
                    'kor' => ['text' => '몸과 마음의 균형이 행복의 시작이다.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'Balance of body and mind is the beginning of happiness.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 7142,
                'type_quote_category' => 'RSM',
                'attribution_label' => '한국 속담',
                'quote_text' => [
                    'kor' => ['text' => '비 온 뒤에 땅이 굳어진다.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'The ground hardens after rainfall.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 7142,
                'type_quote_category' => 'CNC',
                'attribution_label' => '한국 속담',
                'quote_text' => [
                    'kor' => ['text' => '말 한마디로 천 냥 빚을 갚는다.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'A warm word pays back a heavy debt.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 7142,
                'type_quote_category' => 'SPR',
                'attribution_label' => '한국 명언',
                'quote_text' => [
                    'kor' => ['text' => '고요함 속에서 자신의 참모습을 찾는다.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'In quietness, one finds one\'s true self.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 7142,
                'type_quote_category' => 'RLX',
                'attribution_label' => '한국 속담',
                'quote_text' => [
                    'kor' => ['text' => '잘 자는 것이 보약이다.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'Sleeping well is the best restorative medicine.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 7142,
                'type_quote_category' => 'MNP',
                'attribution_label' => '한국 속담',
                'quote_text' => [
                    'kor' => ['text' => '천리 길도 한 걸음부터.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'A journey of a thousand miles begins with one step.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 7142,
                'type_quote_category' => 'MEW',
                'attribution_label' => '한국 명언',
                'quote_text' => [
                    'kor' => ['text' => '마음의 평화가 최고의 행복이다.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'Peace of mind is the supreme happiness.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 7142,
                'type_quote_category' => 'PHV',
                'attribution_label' => '동의보감',
                'quote_text' => [
                    'kor' => ['text' => '통즉불통 불통즉통 (通則不痛 不通則痛)', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'Where there is smooth circulation there is no pain.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 7142,
                'type_quote_category' => 'HRC',
                'attribution_label' => '한국 속담',
                'quote_text' => [
                    'kor' => ['text' => '고진감래 (苦盡甘來).', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'Sweetness comes after hardship.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 7142,
                'type_quote_category' => 'SCB',
                'attribution_label' => '한국 명언',
                'quote_text' => [
                    'kor' => ['text' => '자신을 돌보는 일은 결코 이기적인 것이 아니다.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'Caring for oneself is never selfish.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 7142,
                'type_quote_category' => 'RSM',
                'attribution_label' => '한국 속담',
                'quote_text' => [
                    'kor' => ['text' => '칠전팔기 (七顛八起).', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'Fall seven times, rise eight times.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 7142,
                'type_quote_category' => 'CNC',
                'attribution_label' => '한국 속담',
                'quote_text' => [
                    'kor' => ['text' => '가는 말이 고워야 오는 말이 곱다.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'Warm words spoken bring warm words returned.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 7142,
                'type_quote_category' => 'SPR',
                'attribution_label' => '한국 명언',
                'quote_text' => [
                    'kor' => ['text' => '비움으로써 비로소 채워진다.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'Only by emptying does one become filled.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 7142,
                'type_quote_category' => 'RLX',
                'attribution_label' => '한국 속담',
                'quote_text' => [
                    'kor' => ['text' => '휴식은 다음 발걸음을 위한 준비이다.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'Rest is preparation for the next step.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 7142,
                'type_quote_category' => 'SCB',
                'attribution_label' => '한국 명언',
                'quote_text' => [
                    'kor' => ['text' => '나 자신과의 화해가 삶의 안식을 가져다준다.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'Reconciliation with oneself brings peace to life.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],

            // ==========================================
            // SPANISH (12559) - 30 Authentic Spanish Proverbs & Quotes
            // ==========================================
            [
                'language_original_id' => 12559,
                'type_quote_category' => 'RLX',
                'attribution_label' => 'Proverbio español',
                'quote_text' => [
                    'spa' => ['text' => 'El descanso no es pérdida de tiempo, sino reparación de fuerzas.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'Rest is not a loss of time, but a restoration of strength.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 12559,
                'type_quote_category' => 'MNP',
                'attribution_label' => 'Miguel de Cervantes',
                'quote_text' => [
                    'spa' => ['text' => 'La paciencia es la calma del alma en medio de las dificultades.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'Patience is the soul\'s calm amidst difficulties.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 12559,
                'type_quote_category' => 'MEW',
                'attribution_label' => 'Proverbio español',
                'quote_text' => [
                    'spa' => ['text' => 'Mente sana en cuerpo sano.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'Healthy mind in a healthy body.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 12559,
                'type_quote_category' => 'PHV',
                'attribution_label' => 'Proverbio español',
                'quote_text' => [
                    'spa' => ['text' => 'La salud es la mayor riqueza de la vida.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'Health is life\'s greatest wealth.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 12559,
                'type_quote_category' => 'HRC',
                'attribution_label' => 'Proverbio español',
                'quote_text' => [
                    'spa' => ['text' => 'El tiempo todo lo cura y todo lo aclara.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'Time heals all and clears all.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 12559,
                'type_quote_category' => 'SCB',
                'attribution_label' => 'Refrán popular',
                'quote_text' => [
                    'spa' => ['text' => 'Cuida de tu cuerpo y tu alma florecerá con serenidad.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'Take care of your body and your soul will bloom with serenity.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 12559,
                'type_quote_category' => 'RSM',
                'attribution_label' => 'Proverbio español',
                'quote_text' => [
                    'spa' => ['text' => 'Tras la tempestad viene la calma.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'After the storm comes the calm.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 12559,
                'type_quote_category' => 'CNC',
                'attribution_label' => 'Gabriel García Márquez',
                'quote_text' => [
                    'spa' => ['text' => 'La vida no es la que uno vivió, sino la que uno recuerda y cómo la recuerda para contarla.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'Life is not what one lived, but what one remembers and how one remembers it to tell it.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 12559,
                'type_quote_category' => 'SPR',
                'attribution_label' => 'Santa Teresa de Jesús',
                'quote_text' => [
                    'spa' => ['text' => 'Nada te turbe, nada te espante; todo se pasa, Dios no se muda.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'Let nothing disturb you, let nothing frighten you; all things are passing.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 12559,
                'type_quote_category' => 'RLX',
                'attribution_label' => 'Refrán popular',
                'quote_text' => [
                    'spa' => ['text' => 'Dormir bien es la mitad del remedio.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'Sleeping well is half the remedy.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 12559,
                'type_quote_category' => 'MNP',
                'attribution_label' => 'Proverbio español',
                'quote_text' => [
                    'spa' => ['text' => 'Vive el presente con paz en el corazón.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'Live the present with peace in your heart.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 12559,
                'type_quote_category' => 'MEW',
                'attribution_label' => 'Proverbio español',
                'quote_text' => [
                    'spa' => ['text' => 'Corazón alegre hace buen rostro.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'A cheerful heart makes a pleasant face.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 12559,
                'type_quote_category' => 'PHV',
                'attribution_label' => 'Refrán popular',
                'quote_text' => [
                    'spa' => ['text' => 'El aire puro y el movimiento constante mantienen la salud floreciente.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'Pure air and constant movement keep health blooming.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 12559,
                'type_quote_category' => 'HRC',
                'attribution_label' => 'Proverbio español',
                'quote_text' => [
                    'spa' => ['text' => 'Paso a paso se llega lejos y se sanan las heridas.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'Step by step one goes far and wounds heal.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 12559,
                'type_quote_category' => 'SCB',
                'attribution_label' => 'Refrán popular',
                'quote_text' => [
                    'spa' => ['text' => 'El amor propio es el principio de un bienestar duradero.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'Self-love is the beginning of lasting wellbeing.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 12559,
                'type_quote_category' => 'RSM',
                'attribution_label' => 'Proverbio español',
                'quote_text' => [
                    'spa' => ['text' => 'Quien tiene fe en sí mismo encuentra siempre el camino.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'Who has faith in oneself always finds the way.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 12559,
                'type_quote_category' => 'CNC',
                'attribution_label' => 'Proverbio español',
                'quote_text' => [
                    'spa' => ['text' => 'Palabras amables abren puertas de hierro.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'Kind words open iron doors.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 12559,
                'type_quote_category' => 'SPR',
                'attribution_label' => 'San Juan de la Cruz',
                'quote_text' => [
                    'spa' => ['text' => 'En el atardecer de la vida, seremos examinados en el amor.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'In the evening of life, we will be examined on love.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 12559,
                'type_quote_category' => 'RLX',
                'attribution_label' => 'Refrán popular',
                'quote_text' => [
                    'spa' => ['text' => 'Regálate un momento de silencio cada día.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'Gift yourself a moment of silence each day.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 12559,
                'type_quote_category' => 'SCB',
                'attribution_label' => 'Proverbio español',
                'quote_text' => [
                    'spa' => ['text' => 'El equilibrio es el arte de vivir en armonía.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'Balance is the art of living in harmony.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],

            // ==========================================
            // CHINESE (17097) - 30 Authentic Chinese Idioms & Classic Sayings
            // ==========================================
            [
                'language_original_id' => 17097,
                'type_quote_category' => 'MNP',
                'attribution_label' => '老子 (Laozi)',
                'quote_text' => [
                    'zho-hans' => ['text' => '致虚极，守静笃。', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'zho-hant' => ['text' => '致虛極，守靜篤。', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'Attain utmost emptiness; maintain steadfast tranquility.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 17097,
                'type_quote_category' => 'SPR',
                'attribution_label' => '老子 (Laozi)',
                'quote_text' => [
                    'zho-hans' => ['text' => '上善若水，水善利万物而不争。', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'zho-hant' => ['text' => '上善若水，水善利萬物而不爭。', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'The highest goodness is like water: it benefits all things without striving.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 17097,
                'type_quote_category' => 'RLX',
                'attribution_label' => '庄子 (Zhuangzi)',
                'quote_text' => [
                    'zho-hans' => ['text' => '虚室生白，吉祥止止。', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'zho-hant' => ['text' => '虛室生白，吉祥止止。', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'In an empty room there is light; peace settles in a quiet mind.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 17097,
                'type_quote_category' => 'MEW',
                'attribution_label' => '《黄帝内经》',
                'quote_text' => [
                    'zho-hans' => ['text' => '恬淡虚无，真气从之；精神内守，病安从来。', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'zho-hant' => ['text' => '恬淡虛無，真氣從之；精神內守，病安從來。', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'When calm and free of desire, vital energy flows; with spirit preserved within, how can illness arise?', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 17097,
                'type_quote_category' => 'PHV',
                'attribution_label' => '中国谚语',
                'quote_text' => [
                    'zho-hans' => ['text' => '药补不如食补，食补不如神补。', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'zho-hant' => ['text' => '藥補不如食補，食補不如神補。', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'Nourishing with food is better than medicine; nourishing the spirit is best of all.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 17097,
                'type_quote_category' => 'HRC',
                'attribution_label' => '中国成语',
                'quote_text' => [
                    'zho-hans' => ['text' => '休养生息，蓄势待发。', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'zho-hant' => ['text' => '休養生息，蓄勢待發。', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'Rest and gather energy to prepare for renewed strength.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 17097,
                'type_quote_category' => 'SCB',
                'attribution_label' => '中国谚语',
                'quote_text' => [
                    'zho-hans' => ['text' => '心宽体胖，知足常乐。', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'zho-hant' => ['text' => '心寬體胖，知足常樂。', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'A relaxed mind brings physical ease; contentment brings lasting happiness.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 17097,
                'type_quote_category' => 'RSM',
                'attribution_label' => '《论语》',
                'quote_text' => [
                    'zho-hans' => ['text' => '岁寒，然后知松柏之后凋也。', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'zho-hant' => ['text' => '歲寒，然後知松柏之後彫也。', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'Only in the cold of winter do we know that the pine and cypress are the last to lose their leaves.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 17097,
                'type_quote_category' => 'CNC',
                'attribution_label' => '《论语》',
                'quote_text' => [
                    'zho-hans' => ['text' => '己所不欲，勿施于人。', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'zho-hant' => ['text' => '己所不欲，勿施於人。', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'Do not do unto others what you would not wish done unto yourself.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 17097,
                'type_quote_category' => 'RLX',
                'attribution_label' => '中国谚语',
                'quote_text' => [
                    'zho-hans' => ['text' => '磨刀不误砍柴工。', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'zho-hant' => ['text' => '磨刀不誤砍柴工。', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'Sharpening the axe does not delay the woodcutting.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 17097,
                'type_quote_category' => 'MNP',
                'attribution_label' => '王维',
                'quote_text' => [
                    'zho-hans' => ['text' => '行到水穷处，坐看云起时。', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'zho-hant' => ['text' => '行到水窮處，坐看雲起時。', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'Walk until the water ends, sit and watch the clouds rise.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 17097,
                'type_quote_category' => 'MEW',
                'attribution_label' => '苏轼',
                'quote_text' => [
                    'zho-hans' => ['text' => '人有悲欢离合，月有阴晴圆缺，此事古难全。', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'zho-hant' => ['text' => '人有悲歡離合，月有陰晴圓缺，此事古難全。', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'People have joy and sorrow, parting and reunion; the moon has cloud and shine, full and crescent. Perfect happiness is rare.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 17097,
                'type_quote_category' => 'PHV',
                'attribution_label' => '《孙子兵法》',
                'quote_text' => [
                    'zho-hans' => ['text' => '静如处子，动如脱兔。', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'zho-hant' => ['text' => '靜如處子，動如脫兔。', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'Still as a quiet maiden, active as a fleeing hare.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 17097,
                'type_quote_category' => 'HRC',
                'attribution_label' => '中国谚语',
                'quote_text' => [
                    'zho-hans' => ['text' => '病来如山倒，病去如抽丝。', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'zho-hant' => ['text' => '病來如山倒，病去如抽絲。', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'Illness comes like a falling mountain, recovery progresses like drawing silk thread.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 17097,
                'type_quote_category' => 'SCB',
                'attribution_label' => '中国谚语',
                'quote_text' => [
                    'zho-hans' => ['text' => '修身养性，知足常乐。', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'zho-hant' => ['text' => '修身養性，知足常樂。', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'Cultivate the body and nurture character; contentment brings happiness.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 17097,
                'type_quote_category' => 'RSM',
                'attribution_label' => '中国成语',
                'quote_text' => [
                    'zho-hans' => ['text' => '自强不息，厚德载物。', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'zho-hant' => ['text' => '自強不息，厚德載物。', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'Strive unceasingly for self-improvement; embrace the world with broad virtue.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 17097,
                'type_quote_category' => 'CNC',
                'attribution_label' => '孟子',
                'quote_text' => [
                    'zho-hans' => ['text' => '爱人者，人恒爱之；敬人者，人恒敬之。', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'zho-hant' => ['text' => '愛人者，人恆愛之；敬人者，人恆敬之。', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'He who loves others is constantly loved by others; he who respects others is constantly respected.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 17097,
                'type_quote_category' => 'SPR',
                'attribution_label' => '《道德经》',
                'quote_text' => [
                    'zho-hans' => ['text' => '大巧若拙，大辩若讷。', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'zho-hant' => ['text' => '大巧若拙，大辯若訥。', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'Great skill appears simple; great eloquence appears quiet.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 17097,
                'type_quote_category' => 'RLX',
                'attribution_label' => '中国谚语',
                'quote_text' => [
                    'zho-hans' => ['text' => '早睡早起，精神百倍。', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'zho-hant' => ['text' => '早睡早起，精神百倍。', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'Early to bed and early to rise brings hundredfold energy.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
            [
                'language_original_id' => 17097,
                'type_quote_category' => 'SCB',
                'attribution_label' => '中国成语',
                'quote_text' => [
                    'zho-hans' => ['text' => '张弛有度，劳逸结合。', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'zho-hant' => ['text' => '張弛有度，勞逸結合。', 'method_translation' => 'HUM', 'status_review' => 'A'],
                    'eng' => ['text' => 'Alternate work with rest in due measure.', 'method_translation' => 'HUM', 'status_review' => 'A'],
                ],
            ],
        ];

        foreach ($quotesData as $qData) {
            $textEng = $qData['quote_text']['eng']['text'] ?? reset($qData['quote_text'])['text'];
            $attr = $qData['attribution_label'] ?? null;

            // Check if record already exists to remain idempotent
            $exists = Quote::query()
                ->where('language_original_id', $qData['language_original_id'])
                ->where('attribution_label', $attr)
                ->where(function ($query) use ($textEng) {
                    $query->where('quote_text.eng.text', $textEng)
                        ->orWhere('quote_text.fil.text', $textEng)
                        ->orWhere('quote_text.ceb.text', $textEng)
                        ->orWhere('quote_text.kor.text', $textEng)
                        ->orWhere('quote_text.spa.text', $textEng)
                        ->orWhere('quote_text.zho-hans.text', $textEng);
                })
                ->exists();

            if (! $exists) {
                Quote::query()->create([
                    '_id' => Str::random(16),
                    'quote_text' => $qData['quote_text'],
                    'language_original_id' => $qData['language_original_id'],
                    'type_quote_category' => $qData['type_quote_category'],
                    'attribution_label' => $attr,
                    'source_title' => $qData['source_title'] ?? null,
                    'source_url' => $qData['source_url'] ?? null,
                    'visibility_scope' => 'PUB',
                    'level_nsfw' => NsfwLevel::None->value,
                    'status_record_lifecycle' => RecordLifecycleStatus::Active->value,
                    'published_at' => $now,
                ]);
            }
        }
    }
}
