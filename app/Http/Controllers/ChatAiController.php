<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatAiController extends Controller
{
    public function ask(Request $request)
    {
        // Validate the incoming question
        $request->validate([
            'question' => 'required|string',
        ]);

        $apiKey   = env('OPENROUTER_API_KEY');
        $question = $request->input('question');

        // هنا تحط المحتوى اللي انت عايزه ثابت
        $context = <<<'CTX'
المعهد التكنولجي العالي النشأة والاعتماد
أُنشئ المعهد بموجب القرار الوزاري رقم 1964 لسنة 2020، ويُعد أول معهد عالي تكنولوجي خاص في مصر. هو معتمد من وزارة التعليم العالي والمجلس الأعلى للجامعات، ومدرج ضمن تنسيق مجموع القبول للجامعات والمعاهد
High Tech Institute
.

رؤية ورسالة
يسعى المعهد إلى تخريج كوادر قادرة على تطبيق أحدث تقنيات العلوم التكنولوجية، مع ربط التعليم بسوق العمل واحتياجات الصناعة، مع التركيز على الابتكار والبحث العلمي
High Tech Institute
.

التخصصات والأقسام
يمنح المعهد درجة البكالوريوس التكنولوجي (Technology Bachelor’s Degree) في أربعة أقسام رئيسية:

نظم وشبكات الحاسبات – تخصص برمجة

نظم وشبكات الحاسبات – تخصص شبكات

ميكاترونكس

طاقة جديدة ومتجددة (الطاقة الشمسية وطاقة الرياح)
High Tech Institute
.

نظام الدراسة
مدة الدراسة أربعة أعوام بنظام (2+2):

المرحلتين الأوليين يمنح فيهما الطالب دبلوم تكنولوجي في التخصص.

المراحل اللاحقة تمنح درجة البكالوريوس التكنولوجي في التخصص المختار
High Tech Institute
.

شروط القبول
يقبل المعهد الطلاب الحاصلين على:

الثانوية العامة علمي رياضة (الحد الأدنى لهذا العام 52.4%)

الثانوية الأزهرية شعبة علمي (الحد الأدنى 50% بعد المعادلة)

دبلومات فنية صناعية نظام 3 سنوات (حد أدنى 75%)

دبلومات فنية صناعية نظام 5 سنوات (حد أدنى 70%)

المعاهد الفنية الصناعية (حد أدنى 70%)
High Tech Institute
.

الموقع وطرق التواصل

العنوان: الكيلو 2 طريق بني سويف – القاهرة الزراعي (بجوار بوابة بني سويف مباشرة)

هاتف: 01029337792 – 01200249762
CTX;

        // نبني الميسدجز للـ API
        $messages = [
            [
                'role'    => 'system',
                'content' => "انت مساعد ذكي، جاوب على الأسئلة بناءً على المعلومات دي فقط:\n"
                             . $context,
            ],
            [
                'role'    => 'user',
                'content' => $question,
            ],
        ];

        // نرسل الطلب لـ OpenRouter
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type'  => 'application/json',
        ])->post('https://openrouter.ai/api/v1/chat/completions', [
            'model'    => 'nousresearch/deephermes-3-mistral-24b-preview:free',
            'messages' => $messages,
        ]);

        // نرجّع النتيجة
        if ($response->successful()) {
            return response()->json([
                'answer' => $response
                    ->json()['choices'][0]['message']['content']
                    ?? 'No answer found.',
            ]);
        }

        return response()->json([
            'error'   => 'Failed to get response from OpenRouter.',
            'details' => $response->body(),
        ], 500);
    }
}
