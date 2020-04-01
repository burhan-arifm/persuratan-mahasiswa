    var Ziggy = {
        namedRoutes: {"surat.detail":{"uri":"surat\/{id}","methods":["GET","HEAD"],"domain":null},"surat.cetak":{"uri":"surat\/{id}\/cetak","methods":["GET","HEAD"],"domain":null},"surat.sunting":{"uri":"surat\/{id}\/sunting","methods":["GET","HEAD"],"domain":null},"surat.hapus":{"uri":"surat\/{id}","methods":["DELETE"],"domain":null},"data_surat.semua":{"uri":"data-surat\/semua","methods":["GET","HEAD"],"domain":null},"data_surat.terbaru":{"uri":"data-surat\/terbaru","methods":["GET","HEAD"],"domain":null}},
        baseUrl: process.env.MIX_APP_URL,
        baseProtocol: 'https',
        baseDomain: 'persuratan.test',
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
