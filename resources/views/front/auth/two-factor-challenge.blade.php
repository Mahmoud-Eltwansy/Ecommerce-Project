<x-front-layout title="2FA Challenge">
    <x-slot:breadcrumb>

        <div class="breadcrumbs">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6 col-md-6 col-12">
                        <div class="breadcrumbs-content">
                            <h1 class="page-title">2FA Challenge</h1>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-12">
                        <ul class="breadcrumb-nav">
                            <li><a href="index.html"><i class="lni lni-home"></i> Home</a></li>
                            <li>2FA Challenge</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </x-slot:breadcrumb>

    <div class="account-login section">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3 col-md-10 offset-md-1 col-12">
                    <form class="card login-form" action="{{ route('two-factor.login.store') }}" method="post">
                        @csrf
                        <div class="card-body">
                            <div class="title">
                                <h3>2FA Challenge</h3>
                                <p>Enter 2FA Code</p>
                            </div>

                            <div class="form-group input-group">
                                <label for="reg-code">Code</label>
                                <input class="form-control" type="text" name="code" id="reg-code">
                                @error('code')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div>
                                <div class="form-group input-group">
                                    <label for="recovery_code">Or use a recovery code</label>
                                    <input id="recovery_code" name="recovery_code" type="text" class="form-control">
                                    @error('recovery_code')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                            </div>
                            <div class="button">
                                <button class="btn" type="submit">Verify</button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-front-layout>
