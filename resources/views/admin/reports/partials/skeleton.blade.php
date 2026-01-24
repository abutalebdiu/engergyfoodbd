<template x-if="loading">
    <div class="row">
        @for ($i = 0; $i < 6; $i++)
            <div class="col-md-12 mb-3">
                <div class="card shadow">
                    <div class="card-body">
                        <div class="placeholder-glow">
                            <span class="placeholder col-12"></span>
                            <span class="placeholder col-8"></span>
                        </div>
                    </div>
                </div>
            </div>
        @endfor
    </div>
</template>
