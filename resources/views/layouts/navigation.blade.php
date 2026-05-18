<nav class="bg-white border-b border-gray-200 p-4 flex justify-between">

    <div>
        <a href="{{ route('dashboard') }}">
            Dashboard
        </a>
    </div>

    <div>
        <span style="margin-right:20px;">
            {{ Auth::user()->name }}
        </span>

        <form method="POST"
              action="{{ route('logout') }}"
              style="display:inline;">

            @csrf

            <button type="submit"
                    style="background:red;color:white;padding:8px 12px;border:none;border-radius:5px;cursor:pointer;">

                Log Out

            </button>

        </form>
    </div>

</nav>