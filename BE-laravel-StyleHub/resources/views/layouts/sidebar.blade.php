<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav slimscrollsidebar">
        <div class="sidebar-head">
            <h3>
                <span class="fa-fw open-close"><i class="ti-close ti-menu"></i></span>
                <span class="hide-menu">Navigation</span>
            </h3>
        </div>
        <ul class="nav" id="side-menu">
            <li style="padding: 70px 0 0;">
                <a href="{{ route('dashboard') }}" class="waves-effect">
                    <i class="fa fa-clock-o fa-fw" aria-hidden="true"></i>
                    Dashboard
                </a>
            </li>

            <li>
                <a href="{{ route('categories.index') }}" class="waves-effect">
                    <i class="fa fa-list fa-fw" aria-hidden="true"></i>
                    Danh mục
                </a>
            </li>

            <li>
                <a href="{{ route('products.index') }}" class="waves-effect">
                    <i class="fa fa-cube fa-fw" aria-hidden="true"></i>
                    Sản phẩm
                </a>
            </li>

            <li>
                <a href="{{ route('variants.index') }}" class="waves-effect">
                    <i class="fa fa-tags fa-fw" aria-hidden="true"></i>
                    Biến thể
                </a>
            </li>
            <li>
                <a href="{{ route('images.index') }}" class="waves-effect">
                    <i class="fa fa-image fa-fw" aria-hidden="true"></i>
                    Ảnh sản phẩm
                </a>
            </li>

            <li>
                <a href="{{ route('users.index') }}" class="waves-effect">
                    <i class="fa fa-users fa-fw" aria-hidden="true"></i>
                    Người dùng
                </a>
            </li>
            <li>
                <a href="{{ route('orders.index') }}" class="waves-effect">
                    <i class="fa fa-truck fa-fw" aria-hidden="true"></i>
                    Đơn hàng
                </a>
            </li>






            <li>
                <a href="{{ route('chats.index') }}" class="waves-effect">
                    <i class="fa fa-comments fa-fw" aria-hidden="true"></i>
                    Chat
                </a>
            </li>
        </ul>
    </div>
</div>
