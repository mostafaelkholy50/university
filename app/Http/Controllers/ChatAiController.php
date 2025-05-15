<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatAiController extends Controller
{
    public function ask(Request $request)
    {
        $question = $request->input('question');
        $context = "المعهد التكنولوجي العالي النشأة والاعتماد
أُنشئ المعهد بموجب القرار الوزاري رقم 1964 لسنة 2020، ويُعد أول معهد عالي تكنولوجي خاص في مصر. هو معتمد من وزارة التعليم العالي والمجلس الأعلى للجامعات، ومدرج ضمن تنسيق مجموع القبول للجامعات والمعاهد.

رؤية ورسالة:
يسعى المعهد إلى تخريج كوادر قادرة على تطبيق أحدث تقنيات العلوم التكنولوجية، مع ربط التعليم بسوق العمل واحتياجات الصناعة، مع التركيز على الابتكار والبحث العلمي.

التخصصات والأقسام:
يمنح المعهد درجة البكالوريوس التكنولوجي في أربعة أقسام رئيسية:
- نظم وشبكات الحاسبات (تخصص برمجة).
- نظم وشبكات الحاسبات (تخصص شبكات).
- ميكاترونكس.
- طاقة جديدة ومتجددة (الطاقة الشمسية وطاقة الرياح).

نظام الدراسة:
مدة الدراسة أربعة أعوام بنظام (2+2):
- المرحلتان الأوليان: دبلوم تكنولوجي.
- المرحلتان التاليتان: بكالوريوس تكنولوجي.

شروط القبول:
يقبل المعهد الطلاب الحاصلين على:
- الثانوية العامة (علمي رياضة) بحد أدنى 52.4%.
- الثانوية الأزهرية (شعبة علمي) بحد أدنى 50%.
- الدبلومات الفنية الصناعية (نظام 3/5 سنوات) بحد أدنى 70-75%.

للتواصل:
- العنوان: الكيلو 2 طريق بني سويف - القاهرة الزراعي.
- الهاتف: 01029337792 - 01200249762.";

        $apiKey = 'AIzaSyDNQzhZXDGeZCvHT7aldDyeoj_pGmw9DXw'; // استبدل ده بالمفتاح بتاعك (تأكد إنه صح)
        $apiUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent';

        try {
            $response = Http::post($apiUrl . '?key=' . $apiKey, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $context . "\n\n" . $question],
                        ],
                    ],
                ],
            ]);

            $response->throw();

            $answer = $response->json()['candidates'][0]['content']['parts'][0]['text'] ?? 'لم يتم العثور على إجابة.';

            return response()->json(['answer' => trim($answer)]);

        } catch (\Illuminate\Http\Client\RequestException $e) {
            return response()->json(['error' => 'حدث خطأ في الاتصال بـ Gemini API: ' . $e->getMessage()], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => 'حدث خطأ غير متوقع: ' . $e->getMessage()], 500);
        }
    }
}
