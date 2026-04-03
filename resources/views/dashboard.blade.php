<x-app-layout>
    <style>
        .admin-container { max-width: 1100px; margin: 40px auto; padding: 0 20px; animation: fadeIn 0.4s ease-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .stat-card { background: #fff; padding: 24px; border: 1px solid #e5e7eb; border-radius: 12px; text-align: center; box-shadow: 0 1px 3px rgba(0,0,0,0.02); }
        .stat-value { font-size: 28px; font-weight: 800; color: #111827; }
        .stat-label { font-size: 12px; text-transform: uppercase; color: #6b7280; font-weight: 600; margin-top: 4px; }

        .filter-bar { background: #fff; padding: 16px; border: 1px solid #e5e7eb; border-radius: 12px; margin-bottom: 20px; display: flex; gap: 12px; align-items: center; }
        .search-input { flex-grow: 1; padding: 8px 14px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; outline: none; transition: border 0.2s; }
        .search-input:focus { border-color: #111827; }
        .filter-select { padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; background: #fff; cursor: pointer; }
        .btn-search { padding: 8px 20px; background: #111827; color: #fff; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 14px; }

        .table-wrapper { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.02); }
        table { width: 100%; border-collapse: collapse; font-size: 14px; }
        th { background: #f9fafb; text-align: left; padding: 16px; font-weight: 600; border-bottom: 1px solid #e5e7eb; color: #374151; }
        td { padding: 16px; border-bottom: 1px solid #f3f4f6; vertical-align: top; }
        tr:hover td { background: #fcfcfc; }

        .status-select { width: 100%; padding: 6px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 12px; font-weight: 700; cursor: pointer; margin-bottom: 8px; }
        .admin-comment-area { width: 100%; padding: 8px; font-size: 12px; border: 1px solid #e5e7eb; border-radius: 6px; resize: vertical; min-height: 38px; background: #f9fafb; transition: 0.2s; }
        .admin-comment-area:focus { background: #fff; border-color: #111827; outline: none; }

        .btn-delete { background: none; border: none; color: #9ca3af; cursor: pointer; font-size: 20px; transition: 0.2s; }
        .btn-delete:hover { color: #dc2626; }
        .customer-info span { display: block; font-size: 12px; color: #6b7280; }
    </style>

    <div class="admin-container">
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-value">{{ $stats['total'] }}</div>
                <div class="stat-label">Всего</div>
            </div>
            <div class="stat-card">
                <div class="stat-value" style="color: #2563eb;">{{ $stats['new'] }}</div>
                <div class="stat-label">Новые</div>
            </div>
            <div class="stat-card">
                <div class="stat-value" style="color: #059669;">{{ $stats['closed'] }}</div>
                <div class="stat-label">Закрытые</div>
            </div>
        </div>

        <form action="{{ route('dashboard') }}" method="GET" class="filter-bar">
            <input type="text" name="search" placeholder="Поиск..." value="{{ request('search') }}" class="search-input">
            
            <select name="status" class="filter-select">
                <option value="">Все статусы</option>
                <option value="NEW" {{ request('status') == 'NEW' ? 'selected' : '' }}>NEW</option>
                <option value="IN PROGRESS" {{ request('status') == 'IN PROGRESS' ? 'selected' : '' }}>WORKING</option>
                <option value="CLOSED" {{ request('status') == 'CLOSED' ? 'selected' : '' }}>CLOSED</option>
            </select>

            <button type="submit" class="btn-search">Найти</button>
            
            @if(request('search') || request('status'))
                <a href="{{ route('dashboard') }}" style="font-size: 13px; color: #6b7280; text-decoration: none; padding: 0 10px;">Сбросить</a>
            @endif
        </form>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Клиент</th>
                        <th>Тема и сообщение</th>
                        <th style="width: 250px;">Статус / Заметки</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tickets as $ticket)
                    <tr>
                        <td class="customer-info">
                            <strong>{{ $ticket->customer->name }}</strong>
                            <span>{{ $ticket->customer->email }}</span>
                            <span style="font-size: 10px; margin-top:4px; opacity: 0.7;">{{ $ticket->created_at->format('d.m.Y H:i') }}</span>
                        </td>
                        <td style="line-height: 1.4;">
                            <div style="font-weight: 600; margin-bottom: 4px;">{{ $ticket->subject }}</div>
                            <div style="color: #6b7280; font-size: 13px;">{{ $ticket->message }}</div>
                        </td>
                        <td>
                            <form action="{{ route('tickets.updateStatus', $ticket) }}" method="POST">
                                @csrf @method('PATCH')
                                <select name="status" onchange="this.form.submit()" class="status-select">
                                    <option value="NEW" {{ $ticket->status == 'NEW' ? 'selected' : '' }}>NEW</option>
                                    <option value="IN PROGRESS" {{ $ticket->status == 'IN PROGRESS' ? 'selected' : '' }}>WORKING</option>
                                    <option value="CLOSED" {{ $ticket->status == 'CLOSED' ? 'selected' : '' }}>CLOSED</option>
                                </select>
                                <textarea name="admin_comment" placeholder="Ваша заметка..." onblur="if(this.value != '{{ $ticket->admin_comment }}') this.form.submit()" class="admin-comment-area">{{ $ticket->admin_comment }}</textarea>
                            </form>
                        </td>
                        <td style="text-align: right;">
                            <form action="{{ route('tickets.destroy', $ticket) }}" method="POST" onsubmit="return confirm('Удалить заявку?')">
                                @csrf @method('DELETE')
                                <button class="btn-delete">&times;</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div style="margin-top: 24px;">
            {{ $tickets->links() }}
        </div>
    </div>
</x-app-layout>