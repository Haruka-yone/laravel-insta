<div class="d-flex">

    {{-- 自分のストーリー枠 --}}
    <div class="mx-2 text-center story-wrapper" data-user-id="{{ Auth::id() }}">
        @if($storiesByUser->has(Auth::id()))
            @php $firstStory = $storiesByUser[Auth::id()]->first() @endphp
            <button class="border-0 bg-transparent story-btn position-relative" 
                    data-user-id="{{ Auth::id() }}"
                    data-story-id="{{ $firstStory->id }}">
                <div class="story-ring">
                    @if (Auth::user()->avatar)
                        <img src="{{ Auth::user()->avatar }}" alt="my-story">
                    @else
                        <div class="story-avatar-placeholder">
                            <i class="fa-solid fa-circle-user"></i>
                        </div>
                    @endif
                </div>

                {{-- 右下に＋ボタン --}}
                <a href="{{ route('story.create') }}" 
                   class="btn btn-primary btn-sm rounded-circle position-absolute" 
                   style="bottom:0; right:0; transform:translate(30%,30%); width:24px; height:24px; display:flex; align-items:center; justify-content:center;">
                    <i class="fa-solid fa-plus" style="font-size:12px;"></i>
                </a>
            </button>
        @else
            <div class="position-relative">
                <div class="story-empty">
                    @if (Auth::user()->avatar)
                        <img src="{{ Auth::user()->avatar }}" alt="my-story">
                    @else
                        <div class="story-avatar-placeholder">
                            <i class="fa-solid fa-circle-user"></i>
                        </div>
                    @endif
                </div>

                {{-- 右下に＋ボタン --}}
                <a href="{{ route('story.create') }}" 
                   class="btn btn-primary btn-sm rounded-circle position-absolute" 
                   style="bottom:0; right:0; transform:translate(30%,30%); width:24px; height:24px; display:flex; align-items:center; justify-content:center;">
                    <i class="fa-solid fa-plus" style="font-size:12px;"></i>
                </a>
            </div>
        @endif
        <p class="small">Your story</p>
    </div>

    {{-- 他ユーザーのストーリー --}}
    @foreach($storiesByUser as $userId => $posts)
        @if($userId !== Auth::id())
            @php $firstPost = $posts->first(); @endphp
            <div class="mx-2 text-center story-wrapper" data-user-id="{{ $userId }}">
                <button class="border-0 bg-transparent story-btn" 
                        data-user-id="{{ $userId }}"
                        data-story-id="{{ $firstPost->id }}">
                    <div class="story-ring">
                        @if ($firstPost->user->avatar)
                            <img src="{{ $firstPost->user->avatar }}" alt="story">
                        @else
                            <div class="story-avatar-placeholder">
                                <i class="fa-solid fa-circle-user"></i>
                            </div>
                        @endif
                    </div>
                </button>
                <p class="small">{{ $firstPost->user->name }}</p>
            </div>
        @endif
    @endforeach
</div>

{{-- overlay --}}
<div id="story-overlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:#000; z-index:1050; text-align:center;">
    <button id="story-close" style="position:absolute; top:10px; right:10px; z-index:1060; background:none; border:none; font-size:2rem; color:white; cursor:pointer;">&times;</button>

    <div style="position:relative; top:50%; transform:translateY(-50%); display:inline-block; width:480px; height:640px; border-radius:10px; overflow:hidden; background:#000;">
        <!-- progressbar -->
        <div id="story-progress" style="display:flex; position:absolute; top:5px; left:5px; right:5px; height:4px; gap:2px; z-index:10;">
            <!-- add bar -->
        </div>

        <!-- img -->
        <img id="story-img" src="" style="width:100%; height:100%; object-fit:cover;">

        <!-- user info -->
        <div id="story-user-info" style="position:absolute; top:20px; left:10px; display:flex; align-items:center; gap:8px; color:white; z-index:10;">
            <img id="story-user-avatar" src="" style="width:32px; height:32px; border-radius:50%; object-fit:cover;">
            <div>
                <div id="story-user-name" style="font-weight:bold; font-size:0.9rem;"></div>
                <div id="story-created-at" style="font-size:0.75rem; opacity:0.8;"></div>
            </div>
        </div>

        <!-- description -->
        <div id="story-desc" style="position:absolute; bottom:10px; left:10px; right:10px; color:white; text-align:left; z-index:10; font-size:0.9rem;"></div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const storiesByUser = @json($storiesByUserArray);

    const overlay = document.getElementById("story-overlay");
    const img = document.getElementById("story-img");
    const desc = document.getElementById("story-desc");
    const progressContainer = document.getElementById("story-progress");
    const closeBtn = document.getElementById("story-close");

    const userAvatar = document.getElementById("story-user-avatar");
    const userName = document.getElementById("story-user-name");
    const createdAt = document.getElementById("story-created-at");

    // ページロード時に全ストーリーリングをチェック
    document.querySelectorAll(".story-wrapper").forEach(wrapper => {
        const btn = wrapper.querySelector(".story-btn");
        if (!btn) return;

        const storyId = btn.dataset.storyId;
        const ring = wrapper.querySelector("div"); // .story-ring or .story-seen

        if (!ring) return;

        if (localStorage.getItem("story-seen-" + storyId)) {
            ring.classList.remove("story-ring");
            ring.classList.add("story-seen");
        } else {
            ring.classList.remove("story-seen");
            ring.classList.add("story-ring");
        }
    });

    document.querySelectorAll(".story-btn").forEach(btn => {
        const userId = btn.dataset.userId;
        const storyId = btn.dataset.storyId;

        btn.addEventListener("click", () => {
            const stories = storiesByUser[userId];
            overlay.style.display = "block";
            progressContainer.innerHTML = "";

            // プログレスバー作成
            stories.forEach(() => {
                const bar = document.createElement("div");
                bar.classList.add("story-progress-bar");
                const inner = document.createElement("div");
                inner.classList.add("story-progress-bar-inner");
                bar.appendChild(inner);
                progressContainer.appendChild(bar);
            });

            let current = 0;
            const showStory = idx => {
                const story = stories[idx];

                img.src = story.image;
                desc.textContent = story.description;

                if (story.user_avatar) {
                    userAvatar.src = story.user_avatar;
                    userAvatar.style.display = "block";
                } else {
                    userAvatar.style.display = "none";
                }
                userName.textContent = story.user_name;
                createdAt.textContent = story.created_at;

                const innerBar = progressContainer.children[idx].firstChild;
                innerBar.style.width = "0%"; // reset
                setTimeout(() => innerBar.style.width = "100%", 50);

                setTimeout(() => {
                    if (current + 1 < stories.length) {
                        current++;
                        showStory(current);
                    } else {
                        overlay.style.display = "none";

                        // 見たストーリー単位で localStorage にセット
                        stories.forEach(s => {
                            localStorage.setItem("story-seen-" + s.id, "1");
                        });

                        // リングをグレーに切り替え
                        const ring = btn.querySelector("div");
                        if (ring) {
                            ring.classList.remove("story-ring");
                            ring.classList.add("story-seen");
                        }
                    }
                }, 5000);
            };

            showStory(current);

            closeBtn.onclick = () => {
                overlay.style.display = "none";
                Array.from(progressContainer.children).forEach(bar => bar.firstChild.style.width = "0%");
            };
        });
    });
});
</script>