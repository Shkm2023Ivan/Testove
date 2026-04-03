<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Создать заявку</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen p-6">

    <div class="max-w-md w-full bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
        <div class="bg-gray-900 p-8 text-white">
            <h2 class="text-2xl font-black uppercase tracking-tight">Связаться с нами</h2>
            <p class="text-gray-400 text-xs mt-2 uppercase tracking-widest font-bold">Оставьте заявку и мы перезвоним</p>
        </div>

        <form action="{{ route('tickets.store') }}" method="POST" class="p-8 space-y-4">
            @csrf

            @if(session('success'))
                <div class="p-4 mb-4 text-sm text-green-800 rounded-xl bg-green-50 border border-green-100 font-bold">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="p-4 mb-4 text-sm text-red-800 rounded-xl bg-red-50 border border-red-100">
                    <ul class="list-disc pl-5 font-bold">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-1 ml-1">Ваше имя</label>
                    <input type="text" name="name" required value="{{ old('name') }}"
                           class="w-full rounded-xl border-gray-100 bg-gray-50 text-sm focus:ring-0 focus:border-gray-900 transition-all">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-1 ml-1">Телефон</label>
                    <input type="text" name="phone" required value="{{ old('phone') }}" placeholder="+380..."
                           class="w-full rounded-xl border-gray-100 bg-gray-50 text-sm focus:ring-0 focus:border-gray-900 transition-all">
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase mb-1 ml-1">Email</label>
                <input type="email" name="email" required value="{{ old('email') }}"
                       class="w-full rounded-xl border-gray-100 bg-gray-50 text-sm focus:ring-0 focus:border-gray-900 transition-all">
            </div>

            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase mb-1 ml-1">Тема обращения</label>
                <input type="text" name="subject" required value="{{ old('subject') }}"
                       class="w-full rounded-xl border-gray-100 bg-gray-50 text-sm focus:ring-0 focus:border-gray-900 transition-all">
            </div>

            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase mb-1 ml-1">Сообщение</label>
                <textarea name="message" required rows="4"
                          class="w-full rounded-xl border-gray-100 bg-gray-50 text-sm focus:ring-0 focus:border-gray-900 transition-all resize-none">{{ old('message') }}</textarea>
            </div>

            <button type="submit" 
                    class="w-full bg-gray-900 text-white font-black uppercase text-xs tracking-[0.2em] py-4 rounded-xl hover:bg-black transition-all shadow-lg shadow-gray-200">
                Отправить заявку
            </button>
        </form>
    </div>

</body>
</html>