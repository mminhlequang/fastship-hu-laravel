<style>
    .loading {
        display: none !important;
    }

    .maxui-roller {
        display: inline-block;
        width: 64px;
        height: 64px;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    .maxui-roller div {
        animation: lds-roller 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
        transform-origin: 32px 32px;
    }

    .maxui-roller div:after {
        content: " ";
        display: block;
        position: absolute;
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #fff;
        margin: -3px 0 0 -3px;
    }

    .maxui-roller div:nth-child(1) {
        animation-delay: -0.036s;
    }

    .maxui-roller div:nth-child(1):after {
        top: 50px;
        left: 50px;
    }

    .maxui-roller div:nth-child(2) {
        animation-delay: -0.072s;
    }

    .maxui-roller div:nth-child(2):after {
        top: 54px;
        left: 45px;
    }

    .maxui-roller div:nth-child(3) {
        animation-delay: -0.108s;
    }

    .maxui-roller div:nth-child(3):after {
        top: 57px;
        left: 39px;
    }

    .maxui-roller div:nth-child(4) {
        animation-delay: -0.144s;
    }

    .maxui-roller div:nth-child(4):after {
        top: 58px;
        left: 32px;
    }

    .maxui-roller div:nth-child(5) {
        animation-delay: -0.18s;
    }

    .maxui-roller div:nth-child(5):after {
        top: 57px;
        left: 25px;
    }

    .maxui-roller div:nth-child(6) {
        animation-delay: -0.216s;
    }

    .maxui-roller div:nth-child(6):after {
        top: 54px;
        left: 19px;
    }

    .maxui-roller div:nth-child(7) {
        animation-delay: -0.252s;
    }

    .maxui-roller div:nth-child(7):after {
        top: 50px;
        left: 14px;
    }

    .maxui-roller div:nth-child(8) {
        animation-delay: -0.288s;
    }

    .maxui-roller div:nth-child(8):after {
        top: 45px;
        left: 10px;
    }

    @keyframes lds-roller {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }

    .loader {
        height: 100%;
        width: 100%;
        position: fixed;
        background: rgba(0, 0, 0, 0.3);
        z-index: 99999999;
        display: flex !important;
        top: 0;
    }
</style>
<div class="loading justify-content-center">
    <div class="maxui-roller align-self-center">
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
    </div>
</div>