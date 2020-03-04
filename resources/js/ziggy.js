    var Ziggy = {
        namedRoutes: {"login":{"uri":"login","methods":["GET","HEAD"],"domain":null},"logout":{"uri":"logout","methods":["POST"],"domain":null},"register":{"uri":"register","methods":["GET","HEAD"],"domain":null},"password.request":{"uri":"password\/reset","methods":["GET","HEAD"],"domain":null},"password.email":{"uri":"password\/email","methods":["POST"],"domain":null},"password.reset":{"uri":"password\/reset\/{token}","methods":["GET","HEAD"],"domain":null},"password.update":{"uri":"password\/reset","methods":["POST"],"domain":null},"password.confirm":{"uri":"password\/confirm","methods":["GET","HEAD"],"domain":null},"form_surat":{"uri":"pengajuan\/{kode_surat}","methods":["GET","HEAD"],"domain":null},"ajukan_surat":{"uri":"pengajuan\/ajukan","methods":["POST"],"domain":null},"beranda":{"uri":"\/","methods":["GET","HEAD"],"domain":null},"surat.riwayat":{"uri":"surat","methods":["GET","HEAD"],"domain":null},"surat.detail":{"uri":"surat\/{id}","methods":["GET","HEAD"],"domain":null},"surat.cetak":{"uri":"surat\/{id}\/cetak","methods":["GET","HEAD"],"domain":null},"surat.sunting":{"uri":"surat\/{id}\/sunting","methods":["GET","HEAD"],"domain":null},"surat.edit":{"uri":"surat\/{id}\/sunting","methods":["PUT"],"domain":null},"surat.hapus":{"uri":"surat\/{id}","methods":["DELETE"],"domain":null},"laporan.umum":{"uri":"laporan","methods":["GET","HEAD"],"domain":null},"pengaturan.umum":{"uri":"pengaturan","methods":["GET","HEAD"],"domain":null},"data-surat":{"uri":"surat\/terbaru","methods":["GET","HEAD"],"domain":null}},
        baseUrl: process.env.MIX_APP_URL,
        baseProtocol: 'http',
        baseDomain: 'localhost',
        basePort: false,
        defaultParameters: []
    };

    if (typeof window.Ziggy !== 'undefined') {
        for (var name in window.Ziggy.namedRoutes) {
            Ziggy.namedRoutes[name] = window.Ziggy.namedRoutes[name];
        }
    }

    export {
        Ziggy
    }
