@extends('layouts.client')

@section('title', 'Chi tiết đơn hàng')

@section('content')
<section class="section-b-space pt-0">
  <div class="heading-banner">
    <div class="custom-container container">
      <div class="row align-items-center">
        <div class="col-6">
          <h4>Chi tiết đơn hàng</h4>
        </div>
        <div class="col-6">
          <ul class="breadcrumb float-end">
            <li class="breadcrumb-item"> <a href="{{ route('client.home') }}">Trang chủ</a></li>
            <li class="breadcrumb-item active">Chi tiết đơn hàng</li>
          </ul>
        </div>

      </div>
    </div>
  </div>

</section>

<section class="section-b-space pt-0">
  <div class="custom-container container order-tracking">
    <div class="row g-4">
      <div class="col-12">
        <div class="order-table">
          <div class="table-responsive theme-scrollbar">
            <table class="table">
              <thead>
                <tr>
                  <th>Mã đơn</th>
                  <th>Ngày đặt</th>
                  <th>Người nhận</th>
                  <th>SĐT</th>
                  <th>Địa chỉ</th>
                  <th>Giao bởi</th>
                  <th>Thanh toán</th>
                  <th>Tải Hóa Đơn</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>#{{ $order->order_code ?? $order->id }}</td>
                  <td>{{ $order->created_at->locale('vi')->translatedFormat('d/m/Y') }}</td>
                  <td>{{ $order->address->full_name }}</td>
                  <td>{{ $order->address->phone }}</td>
                  <td style="max-width: 300px; word-wrap: break-word; white-space: normal;">
                    {{ $order->address->address }},
                    {{ $order->address->ward->name ?? '' }},
                    {{ $order->address->district->name ?? '' }},
                    {{ $order->address->province->name ?? '' }}
                  </td>

                  <td>{{ $order->courier_name ?? 'Đang xử lý' }}</td>
                  <td>{{ $order->payment_method === 'cod' ? 'Thanh toán khi nhận hàng' : 'Đã thanh toán' }}</td>
                  <td>
                    <a href="{{ route('client.orders.invoice', $order->id) }}"
                      class="btn btn-sm btn-outline-primary" target="_blank">
                      <i class="bi bi-download"></i> Hóa đơn
                    </a>
                  </td>

                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="col-lg-5">
        <div class="tracking-box">
          <div class="sidebar-title">
            <div class="loader-line"></div>
            <h4>Tiến độ/Trạng thái</h4>
          </div>
          @php
          use Illuminate\Support\Str;

          /**
          * 1) Chuẩn hoá trạng thái → key chuẩn
          * Bao phủ các trạng thái GHN (ảnh bạn gửi) + enum DB (pending, confirmed, shipping, completed, cancelled, returning, returned)
          */
          $alias = [
          // chuẩn chung
          'pending' => 'pending',
          'confirmed' => 'confirmed',
          'shipping' => 'shipping',
          'completed' => 'delivered', // completed coi như đã giao
          'cancelled' => 'cancelled',
          'returning' => 'returning',
          'returned' => 'returned_received',

          // GHN (tiếng Việt)
          'cho-xac-nhan' => 'pending',
          'da-xac-nhan' => 'confirmed',
          'dang-chuan-bi-hang' => 'preparing',
          'dang-chuan-bi' => 'preparing',
          'cho-ban-giao-vc' => 'handover',
          'cho-ban-giao' => 'handover',
          'dang-giao' => 'shipping',
          'giao-that-bai' => 'delivery_failed',
          'da-giao' => 'delivered',
          'da-huy' => 'cancelled',

          'yeu-cau-tra-hang' => 'return_requested',
          'dang-tra-hang-ve' => 'returning',
          'da-nhan-hang-tra' => 'returned_received',

          'yeu-cau-doi-hang' => 'exchange_requested',
          'da-doi-xong' => 'exchanged_done',

          'dang-hoan-tien' => 'refunding',
          'da-hoan-tien' => 'refunded',

          // một số biến thể EN
          'processing' => 'preparing',
          'delivering' => 'shipping',
          'failed' => 'delivery_failed',
          ];

          /** 2) Thứ tự toàn flow (để tô %/so sánh) */
          $flowOrder = [
          'pending','confirmed','preparing','handover','shipping','delivery_failed','delivered','cancelled',
          'return_requested','returning','returned_received',
          'exchange_requested','exchanged_done',
          'refunding','refunded',
          ];

          /** 3) Nhãn + màu + icon cho mỗi key chuẩn (VI) */
          $meta = [
          'pending' => ['label'=>'Chờ xác nhận', 'bg'=>'#FFF8E1','color'=>'#b26a00','icon'=>'⏳'],
          'confirmed' => ['label'=>'Đã xác nhận', 'bg'=>'#E8F5E9','color'=>'#2e7d32','icon'=>'✓'],
          'preparing' => ['label'=>'Đang chuẩn bị hàng','bg'=>'#FFF8E1','color'=>'#b26a00','icon'=>'📦'],
          'handover' => ['label'=>'Chờ bàn giao VC', 'bg'=>'#FFF8E1','color'=>'#b26a00','icon'=>'🤝'],
          'shipping' => ['label'=>'Đang giao', 'bg'=>'#E3F2FD','color'=>'#1565c0','icon'=>'🚚'],
          'delivery_failed' => ['label'=>'Giao thất bại', 'bg'=>'#FFEBEE','color'=>'#c62828','icon'=>'⚠️'],
          'delivered' => ['label'=>'Đã giao', 'bg'=>'#E8F5E9','color'=>'#2e7d32','icon'=>'✅'],
          'cancelled' => ['label'=>'Đã hủy', 'bg'=>'#FFEBEE','color'=>'#c62828','icon'=>'✖'],

          'return_requested' => ['label'=>'Yêu cầu trả hàng', 'bg'=>'#FFF3E0','color'=>'#ef6c00','icon'=>'📩'],
          'returning' => ['label'=>'Đang trả hàng về', 'bg'=>'#FFF3E0','color'=>'#ef6c00','icon'=>'↩'],
          'returned_received' => ['label'=>'Đã nhận hàng trả', 'bg'=>'#E0F2F1','color'=>'#00695c','icon'=>'📦✅'],

          'exchange_requested' => ['label'=>'Yêu cầu đổi hàng', 'bg'=>'#FFF3E0','color'=>'#ef6c00','icon'=>'🔁'],
          'exchanged_done' => ['label'=>'Đã đổi xong', 'bg'=>'#E8F5E9','color'=>'#2e7d32','icon'=>'🔁✅'],

          'refunding' => ['label'=>'Đang hoàn tiền', 'bg'=>'#E3F2FD','color'=>'#1565c0','icon'=>'💸'],
          'refunded' => ['label'=>'Đã hoàn tiền', 'bg'=>'#E8F5E9','color'=>'#2e7d32','icon'=>'💸✅'],
          ];

          /** Helper: chuẩn hoá string trạng thái -> key */
          $norm = function($s) use ($alias, $meta){
          $slug = Str::slug((string)$s, '-');
          return $alias[$slug] ?? (array_key_exists($slug,$meta) ? $slug : 'pending');
          };

          /** 4) Dữ liệu steps từ controller */
          $steps = collect($order->tracking_steps ?? [])->values();
          $total = $steps->count();

          /** 5) Xác định bước hiện tại (ưu tiên theo orders.status, fallback: bước cuối) */
          $currentKey = $norm($order->status ?? ($steps->last()['status'] ?? 'pending'));
          $currentIndex = $total ? $total - 1 : 0;
          if ($total) {
          foreach ($steps as $i => $st) {
          if ($norm($st['status'] ?? '') === $currentKey) $currentIndex = $i; // lấy lần xuất hiện sau cùng
          }
          }

          /** 6) Ẩn bớt khi quá dài */
          $MAX_SHOW = 5;
          $hiddenCount = max(0, $total - $MAX_SHOW);
          $visibleFrom = max(0, $total - $MAX_SHOW);
          @endphp

          <div id="timeline-box" style="background:#fff;border-radius:16px;padding:18px 16px;box-shadow:0 10px 24px rgba(0,0,0,.06);">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">
              <h4 style="margin:0;font-weight:700;">Trạng thái đơn hàng</h4>
              @if($hiddenCount > 0)
              <div>
                <button id="tl-more" type="button"
                  style="padding:6px 10px;border-radius:10px;border:1px solid #eee;background:#fafafa;cursor:pointer;">
                  Xem tất cả ({{ $total }})
                </button>
                <button id="tl-less" type="button"
                  style="padding:6px 10px;border-radius:10px;border:1px solid #eee;background:#fafafa;cursor:pointer;display:none;">
                  Thu gọn
                </button>
              </div>
              @endif
            </div>

            @if($total)
            <div id="tl" style="position:relative;padding-left:42px;max-height:520px;overflow-y:auto;">
              <!-- line nền -->
              <div style="position:absolute;left:20px;top:0;bottom:0;width:2px;background:#ececec;"></div>
              <!-- line tô đến bước hiện tại -->
              <div id="tl-fill" style="position:absolute;left:20px;top:0;width:2px;background:#c69c6d;height:0;border-radius:2px;"></div>

              @php $lastDate = null; @endphp
              @foreach ($steps as $i => $step)
              @php
              $k = $norm($step['status'] ?? '');
              $info = $meta[$k] ?? ['label'=>$step['status'] ?? 'Trạng thái','bg'=>'#EEE','color'=>'#444','icon'=>'•'];
              $isCurrent = ($i === $currentIndex);
              $isOlder = ($i < $visibleFrom);
                $date=$step['date'] ?? '' ; $time=$step['time'] ?? '' ;
                @endphp

                {{-- nhãn ngày --}}
                @if($lastDate !==$date && $date)
                <div data-older="{{ $isOlder?1:0 }}" style="display:{{ $isOlder?'none':'inline-block' }};
               margin:14px 0 8px 0;padding:6px 12px;border-radius:999px;background:#f5f5f7;color:#444;font-weight:700;">
                {{ $date }}
            </div>
            @php $lastDate = $date; @endphp
            @endif

            <div class="tl-step" data-current="{{ $isCurrent?1:0 }}" data-older="{{ $isOlder?1:0 }}"
              style="position:relative;margin:0 0 12px 0; display:{{ $isOlder?'none':'block' }};">
              <!-- chấm -->
              <div style="
            position:absolute;left:-1px;top:22px;width:16px;height:16px;border-radius:50%;
            background:{{ $isCurrent ? '#2e7d32' : '#dcdcdc' }};
            box-shadow:0 0 0 2px {{ $isCurrent ? '#A5D6A7' : '#ececec' }};
            outline:4px solid #fff;"></div>

              <!-- thẻ -->
              <div style="
            display:flex;align-items:center;gap:14px;background:#fff;border:1px solid #f0f0f0;
            border-radius:14px;padding:12px 14px;box-shadow:0 6px 16px rgba(0,0,0,.06);
            {{ $isCurrent ? 'box-shadow:0 10px 22px rgba(198,156,109,.22);outline:2px solid #c69c6d22;' : '' }}">
                <div style="flex:0 0 auto;width:38px;height:38px;border-radius:50%;display:flex;align-items:center;justify-content:center;background:#fafafa;border:1px solid #eee;font-weight:700;">
                  {{ $info['icon'] }}
                </div>
                <span style="flex:0 0 auto;min-width:150px;text-align:center;font-weight:700;font-size:.85rem;
                         padding:6px 10px;border-radius:999px;background:{{ $info['bg'] }};color:{{ $info['color'] }};">
                  {{ $info['label'] }}
                </span>
                <div style="flex:1 1 auto;">
                  <div style="font-weight:700;color:#333;">{{ $date ?: '—' }}</div>
                  <div style="margin-top:2px;color:#7a7a7a;">{{ $step['status'] ?? '' }}</div> {{-- nếu muốn bỏ english thì xoá dòng này --}}
                </div>
                @if($time)
                <span style="margin-left:auto;font-variant-numeric:tabular-nums;font-weight:800;color:#222;">{{ $time }}</span>
                @endif

              </div>
            </div>
            @endforeach
          </div>
          @else
          <div style="background:#fff;border-radius:14px;padding:14px 16px;border:1px solid #f0f0f0;box-shadow:0 6px 16px rgba(0,0,0,.06);">
            <div style="font-weight:700;">Chưa có cập nhật</div>
            <div style="color:#7a7a7a;margin-top:4px;">Đơn hàng sẽ sớm được cập nhật trạng thái.</div>
          </div>
          @endif
        </div>

        @if($hiddenCount > 0)
        <script>
          (function() {
            const more = document.getElementById('tl-more');
            const less = document.getElementById('tl-less');
            const list = document.getElementById('tl');
            const fill = document.getElementById('tl-fill');

            function setFillHeight() {
              if (!list || !fill) return;
              const cur = list.querySelector('.tl-step[data-current="1"]');
              if (!cur) return;
              const rWrap = list.getBoundingClientRect();
              const rCur = cur.getBoundingClientRect();
              const h = (rCur.top - rWrap.top) + rCur.height / 2;
              fill.style.height = h + 'px';
            }

            if (more) {
              more.addEventListener('click', function() {
                document.querySelectorAll('#timeline-box [data-older="1"]').forEach(el => el.style.display = 'block');
                more.style.display = 'none';
                if (less) less.style.display = 'inline-block';
                setTimeout(setFillHeight, 50);
              });
            }
            if (less) {
              less.addEventListener('click', function() {
                document.querySelectorAll('#timeline-box [data-older="1"]').forEach(el => el.style.display = 'none');
                less.style.display = 'none';
                if (more) more.style.display = 'inline-block';
                list.scrollTop = list.scrollHeight;
                setTimeout(setFillHeight, 50);
              });
            }

            // lần đầu
            setFillHeight();
            window.addEventListener('resize', setFillHeight);
          })();
        </script>
        @endif

      </div>
    </div>

    @php

    // Chuẩn hoá trạng thái và map % bắt đầu
    $alias = [
    'pending'=>'pending','confirmed'=>'confirmed','shipping'=>'shipping','completed'=>'delivered',
    'cancelled'=>'cancelled','processing'=>'preparing','delivering'=>'shipping','failed'=>'delivery_failed',
    'cho-xac-nhan'=>'pending','da-xac-nhan'=>'confirmed','dang-chuan-bi-hang'=>'preparing',
    'cho-ban-giao-vc'=>'handover','dang-giao'=>'shipping','giao-that-bai'=>'delivery_failed',
    'da-giao'=>'delivered','da-huy'=>'cancelled',
    ];
    $statusKey = $alias[Str::slug($order->status ?? 'pending','-')] ?? 'pending';

    // % xuất phát theo trạng thái (đã giao/hủy = 100)
    $startPctMap = [
    'pending'=>0, 'confirmed'=>12, 'preparing'=>28, 'handover'=>40,
    'shipping'=>65, 'delivery_failed'=>65, 'delivered'=>100, 'cancelled'=>100,
    ];
    $startPct = $startPctMap[$statusKey] ?? 0;

    $badge = [
    'pending' => ['Chờ xác nhận','#FFF8E1','#b26a00'],
    'confirmed' => ['Đã xác nhận','#E8F5E9','#2e7d32'],
    'preparing' => ['Đang chuẩn bị','#FFF8E1','#b26a00'],
    'handover' => ['Chờ bàn giao VC','#FFF8E1','#b26a00'],
    'shipping' => ['Đang giao','#E3F2FD','#1565c0'],
    'delivery_failed' => ['Giao thất bại','#FFEBEE','#c62828'],
    'delivered' => ['Đã giao','#E8F5E9','#2e7d32'],
    'cancelled' => ['Đã hủy','#FFEBEE','#c62828'],
    ][$statusKey] ?? ['Đang xử lý','#F5F5F7','#555'];

    $eta = optional($order->expected_delivery_date)->format('d/m/Y') ?? '—';
    $tracking = $order->ghn_order_code ?? $order->shipping_tracking_code ?? '—';
    @endphp

    <div class="col-lg-7">
      <div style="background:#fff;border-radius:16px;padding:16px;box-shadow:0 10px 24px rgba(0,0,0,.08);">
        <div style="display:flex;justify-content:space-between;align-items:center;gap:12px;margin-bottom:10px;">
          <h4 style="margin:0;font-weight:800;">Hành Trình Giao Hàng (Mini-Game)</h4>
          <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
            <span style="padding:6px 10px;border-radius:999px;background:{{ $badge[1] }};color:{{ $badge[2] }};font-weight:700;font-size:.85rem;">
              {{ $badge[0] }}
            </span>
            <span style="color:#666;font-size:.9rem;">ETA: <strong>{{ $eta }}</strong></span>
          </div>
        </div>

        <div id="mg" style="background:#fff;border:1px solid #eee;border-radius:14px;padding:12px;">
          <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;gap:8px;flex-wrap:wrap;">
            <div style="color:#666;">Mã vận đơn: <strong id="mg-trk">{{ $tracking }}</strong></div>
            <div style="display:flex;gap:8px;">
              <button id="btn-play" type="button" style="padding:6px 10px;border-radius:10px;border:1px solid #eee;background:#fafafa;cursor:pointer;">Play ▶</button>
              <button id="btn-boost" type="button" style="padding:6px 10px;border-radius:10px;border:1px solid #eee;background:#fafafa;cursor:pointer;">Cổ vũ 🚀</button>
              <button id="btn-reset" type="button" style="padding:6px 10px;border-radius:10px;border:1px solid #eee;background:#fafafa;cursor:pointer;">↻</button>
            </div>
          </div>

          <!-- sân chơi -->
          <div id="stage" style="position:relative;height:180px;border:1px solid #eee;border-radius:12px;background:linear-gradient(#f9fafb,#ffffff);overflow:hidden;">
            <!-- đường -->
            <div id="road" style="position:absolute;left:6%;right:6%;top:58%;height:12px;background:#5f6062;border-radius:8px;box-shadow:inset 0 0 0 2px #444;overflow:hidden;">
              <div id="dash" style="position:absolute;left:0;top:4px;height:4px;width:200%;background:
            repeating-linear-gradient(90deg,#f3f4f6 0 16px, transparent 16px 34px);"></div>
            </div>
            <!-- mốc -->
            @php $marks=[8,32,60,90]; @endphp
            @foreach($marks as $m)
            <div style="position:absolute;left:{{ $m }}%;top:54%;
               width:14px;height:14px;border-radius:50%;background:#d1b18b;box-shadow:0 0 0 3px #eddcc8;"></div>
            @endforeach
            <!-- Kho / Bạn -->
            <div style="position:absolute;left:2%;top:18%;text-align:center;">
              <div style="font-size:28px;">🏬</div>
              <div style="color:#666;">Kho</div>
            </div>
            <div style="position:absolute;right:2%;top:18%;text-align:center;">
              <div style="font-size:28px;">👤</div>
              <div style="color:#666;">Bạn</div>
            </div>

            <!-- xe -->
            <div id="truck" style="position:absolute;left:6%;top:38%;transform:translateX(0) translateY(0);transition:transform .15s;">
              <div style="font-size:36px;filter:drop-shadow(0 2px 2px rgba(0,0,0,.25));transform:scaleX(-1);transform-origin:center;">🚚</div>
            </div>


            <!-- chướng ngại & quà -->
            <div id="obstacles"></div>
            <div id="gifts"></div>

            <!-- HUD -->
            <div id="hud" style="position:absolute;left:10px;top:8px;color:#333;font-weight:700;background:#fff8;border-radius:8px;padding:4px 8px;">
              ⭐ <span id="score">0</span> &nbsp; ❤️ <span id="life">3</span>
              <span style="color:#777;margin-left:8px;">(Space/Chạm để nhảy)</span>
            </div>

            <!-- thông báo -->
            <div id="banner" style="position:absolute;left:50%;top:50%;transform:translate(-50%,-50%);display:none;
             background:#fff;border:1px solid #eee;border-radius:12px;padding:12px 16px;font-weight:800;"></div>
          </div>

          <!-- thanh tiến độ (ẩn số %) -->
          @php $showBar = $statusKey !== 'delivered' && $statusKey !== 'cancelled'; @endphp
          @if($showBar)
          <div style="margin-top:10px;">
            <div style="height:10px;background:#f1f1f3;border-radius:999px;overflow:hidden;">
              <div id="bar" style="height:100%;width:0;background:linear-gradient(90deg,#c69c6d,#d8b38d);"></div>
            </div>
          </div>
          @endif
        </div>
      </div>
    </div>
    <script>
      (function() {
        /* ==== Tuning ==== */
        const BASE_SPEED = 0.026; // %/frame (~14s về đích)
        const BOOST_STEP   = 0.20;   // mỗi lần cổ vũ +0.20 (nhẹ hơn)
const BOOST_MAX    = 0.60;   // trần tối đa của boost
const BOOST_DECAY  = 0.992;  // giảm boost dần mỗi frame
        const SPAWN_OBS_MS = 1100; // spawn chướng ngại (ms)
        const SPAWN_GIF_MS = 1500; // spawn quà (ms)

        // >>> Vật lý nhảy (đã tăng)
        const JUMP_IMPULSE = -12; // mạnh hơn (trước -10)
        const GRAVITY = 0.50; // rơi chậm hơn (trước 0.9)
        const MAX_JUMPS = 2; // double-jump
        const COYOTE_MS = 120; // bấm muộn vẫn nhảy
        const OB_MIN_H = 12,
          OB_MAX_H = 20; // thùng thấp hơn

        /* ==== State ==== */
        let running = false,
          boost = 0;
        let y = 0,
          vy = 0,
          dist = 0,
          score = 0,
          life = 3;
        let lastTs = 0,
          obsTimer = 0,
          giftTimer = 0;
        let jumpsLeft = MAX_JUMPS,
          lastGround = 0;

        /* ==== DOM ==== */
        const truck = document.getElementById('truck');
        const dash = document.getElementById('dash');
        const bar = document.getElementById('bar');
        const stage = document.getElementById('stage');
        const obsC = document.getElementById('obstacles');
        const giftC = document.getElementById('gifts');
        const scoreEl = document.getElementById('score');
        const lifeEl = document.getElementById('life');
        const btnPlay = document.getElementById('btn-play');
        const btnBoost = document.getElementById('btn-boost');
        const btnReset = document.getElementById('btn-reset');
        const banner = document.getElementById('banner');

        function setDist(p) {
          dist = Math.max(0, Math.min(100, p));
          truck.style.transform = `translateX(${dist*0.88}%) translateY(${y}px)`;
          if (bar) bar.style.width = dist + '%';
        }

        function jump() {
          if (!running) return;
          const now = performance.now();
          // on ground hoặc trong coyote time, hoặc còn lượt nhảy thứ 2
          if (jumpsLeft > 0 || (now - lastGround) < COYOTE_MS) {
            vy = JUMP_IMPULSE;
            y += -1; // nảy nhẹ để rời mặt đất
            if (jumpsLeft > 0) jumpsLeft--;
          }
        }

        function addObstacle() {
          const el = document.createElement('div');
          el.className = 'ob';
          const h = OB_MIN_H + Math.random() * (OB_MAX_H - OB_MIN_H);
          el.style.cssText = `position:absolute;right:-30px;top:calc(58% - ${h}px);
      width:${20+Math.random()*16}px;height:${h}px;background:#6d4c41;border-radius:3px;
      box-shadow:0 2px 0 #4e342e;`;
          obsC.appendChild(el);
          setTimeout(() => el.remove(), 9000);
        }

        function addGift() {
          const el = document.createElement('div');
          el.className = 'gift';
          el.textContent = '📦';
          el.style.cssText = `position:absolute;right:-30px;top:${45+Math.random()*25}%;font-size:20px;`;
          giftC.appendChild(el);
          setTimeout(() => el.remove(), 8000);
        }

        function confetti() {
          for (let i = 0; i < 30; i++) {
            const s = document.createElement('span');
            s.textContent = ['🎉', '✨', '💫', '🎊'][i % 4];
            s.style.cssText = `position:fixed;left:${10+Math.random()*80}vw;top:-10vh;
        font-size:${18+Math.random()*14}px;transition:transform 1.2s linear,opacity 1.2s;opacity:1;`;
            document.body.appendChild(s);
            requestAnimationFrame(() => {
              s.style.transform = `translateY(110vh) rotate(${Math.random()*360}deg)`;
              s.style.opacity = '0';
            });
            setTimeout(() => s.remove(), 1400);
          }
        }

        function aabb(a, b) {
          const ra = a.getBoundingClientRect(),
            rb = b.getBoundingClientRect();
          return !(ra.right < rb.left || ra.left > rb.right || ra.bottom < rb.top || ra.top > rb.bottom);
        }

        function loop(ts) {
          if (!running) return;
          if (!lastTs) lastTs = ts;
          const dt = ts - lastTs;
          lastTs = ts;

          // nền đường trôi
          const px = (parseFloat(dash.dataset.off || '0') - (0.08 * dt) - (boost * 0.05 * dt));
          dash.dataset.off = px;
          dash.style.transform = `translateX(${px}px)`;

          // nhảy
          vy += GRAVITY * (dt / 16);
          y += vy * (dt / 16);
          if (y > 0) { // chạm đất
            y = 0;
            vy = 0;
            jumpsLeft = MAX_JUMPS;
            lastGround = ts;
          }
          truck.style.transform = `translateX(${dist*0.88}%) translateY(${y}px)`;

          // tiến độ
          const inc = (BASE_SPEED + boost) * (dt / 16);
          setDist(dist + inc);

          // spawn
          obsTimer += dt;
          if (obsTimer >= SPAWN_OBS_MS) {
            obsTimer = 0;
            addObstacle();
          }
          giftTimer += dt;
          if (giftTimer >= SPAWN_GIF_MS) {
            giftTimer = 0;
            addGift();
          }

          // move & collision
          document.querySelectorAll('#obstacles .ob').forEach(el => {
            el.style.right = (parseFloat(el.style.right) + (0.14 * dt) + (boost * 0.09 * dt)) + 'px';
            if (aabb(truck, el) && Math.abs(y) < 2) {
              el.style.top = '120%';
              life--;
              lifeEl.textContent = life;
              truck.animate([{
                  transform: truck.style.transform + ' translateX(0)'
                },
                {
                  transform: truck.style.transform + ' translateX(-6px)'
                },
                {
                  transform: truck.style.transform + ' translateX(0)'
                }
              ], {
                duration: 200
              });
              if (life <= 0) {
                gameOver();
                return;
              }
            }
          });
          document.querySelectorAll('#gifts .gift').forEach(el => {
            el.style.right = (parseFloat(el.style.right) + (0.12 * dt) + (boost * 0.08 * dt)) + 'px';
            if (aabb(truck, el)) {
              el.remove();
              score += 10;
              scoreEl.textContent = score;
            }
          });

          boost = Math.max(0, boost * Math.pow(0.995, dt / 16)); // giảm dần

          if (dist >= 100) {
            win();
            return;
          }
          requestAnimationFrame(loop);
        }

        function win() {
          running = false;
          setDist(100);
          banner.textContent = 'Giao thành công! 🎉';
          banner.style.display = 'block';
          confetti();
        }

        function gameOver() {
          running = false;
          banner.textContent = 'Toang! Thử lại nhé 😅';
          banner.style.display = 'block';
        }

        // Controls
        document.getElementById('btn-play').onclick = () => {
          banner.style.display = 'none';
          score = 0;
          life = 3;
          scoreEl.textContent = 0;
          lifeEl.textContent = 3;
          setDist(0);
          boost = 0;
          lastTs = 0;
          obsTimer = 0;
          giftTimer = 0;
          jumpsLeft = MAX_JUMPS;
          lastGround = performance.now();
          running = true;
          requestAnimationFrame(loop);
        };
        document.getElementById('btn-boost').onclick = () => {
          boost += BOOST_STEP;
        };
        document.getElementById('btn-reset').onclick = () => {
          running = false;
          banner.style.display = 'none';
          setDist(0);
          scoreEl.textContent = 0;
          lifeEl.textContent = 3;
          boost = 0;
          jumpsLeft = MAX_JUMPS;
          y = 0;
          vy = 0;
        };

        // Space / chạm để nhảy (mobile support)
        document.addEventListener('keydown', e => {
          if (e.code === 'Space') {
            e.preventDefault();
            jump();
          }
        });
        stage.addEventListener('click', jump);
        stage.addEventListener('touchstart', (e) => {
          e.preventDefault();
          jump();
        }, {
          passive: false
        });

        // xe đứng tại 0% lúc load
        setDist(0);
      })();
    </script>


    <div class="col-12">
      <div class="order-table tracking-table">
        <div class="table-responsive theme-scrollbar">
          <table class="table">
            <thead>
              <tr>
                <th>No.</th>
                <th>Sản phẩm</th>
                <th>Số lượng</th>
                <th>Giá </th>
                <th>Tổng</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($order->orderItems as $index => $item)
              @php
              $variantValues = json_decode($item->variant_values ?? '{}', true);
              @endphp
              <tr>
                <td>{{ $index + 1 }}.</td>
                <td>
                  <div class="cart-box">
                    <a href="">
                      <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product_name }}">
                    </a>
                    <div>
                      <a href="">
                        <h5>{{ $item->product_name }}</h5>
                      </a>
                      <p>Brand: <span>{{ $item->product->brand->name ?? 'N/A' }}</span></p>

                      @php
                      $variantValues = json_decode($item->variant_values ?? '{}', true);
                      @endphp

                      @foreach ($variantValues as $key => $value)
                      <p>{{ ucfirst($key) }}: <span>{{ $value }}</span></p>
                      @endforeach
                    </div>
                  </div>
                </td>
                <td>{{ $item->quantity }}</td>
                <td>${{ number_format($item->price, 2) }}</td>
                <td>${{ number_format($item->price * $item->quantity, 2) }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
          <div style="max-width: 600px; margin-left: auto; padding: 20px; border: 1px solid #eee; background-color: #fff;">
            {{-- Phương thức thanh toán --}}
            <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
              <span style="font-weight: 500;">Phương thức thanh toán:</span>
              <span style="font-weight: 600;">{{ $order->paymentMethod->name ?? '---' }}</span>
            </div>

            {{-- Tạm tính --}}
            <div style="display: flex; justify-content: space-between; margin-bottom: 6px;">
              <span>Tạm tính:</span>
              <span>{{ number_format($order->subtotal, 0, ',', '.') }}đ</span>
            </div>

            {{-- Giảm giá nếu có --}}
            @if ($order->discount_amount > 0)
            <div style="display: flex; justify-content: space-between; margin-bottom: 6px;">
              <span>Mã giảm giá:</span>
              <span style="color: green;">-{{ number_format($order->discount_amount, 0, ',', '.') }}đ</span>
            </div>
            @endif

            {{-- Thuế nếu có --}}
            <div style="display: flex; justify-content: space-between; margin-bottom: 6px;">
              <span>Thuế (VAT):</span>
              <span>{{ number_format($order->tax_amount, 0, ',', '.') }}đ</span>
            </div>

            {{-- Phí vận chuyển --}}
            <div style="display: flex; justify-content: space-between; margin-bottom: 6px;">
              <span>Phí vận chuyển:</span>
              <span>{{ number_format($order->shipping_fee, 0, ',', '.') }}đ</span>
            </div>

            {{-- Đường gạch ngang --}}
            <hr style="margin: 16px 0; border-top: 1px solid #ddd;">

            {{-- Tổng cộng --}}
            <div style="display: flex; justify-content: space-between; font-weight: bold; font-size: 16px;">
              <span style="text-transform: uppercase;">Tổng cộng:</span>
              <span style="color: #e53935;">{{ number_format($order->total_amount, 0, ',', '.') }}đ</span>
            </div>
          </div>

        </div>
      </div>

    </div>
  </div>
  </div>
</section>


@endsection