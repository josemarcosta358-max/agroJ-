# AgroJá — Protótipo Web (Frontend)

Aplicação web estática — apenas HTML e CSS, com um pequeno ficheiro JavaScript só para
as interacções que realmente precisam disso (enviar mensagem no chat, mostrar o modal de
sucesso ao aceitar/publicar uma vaga). A navegação entre ecrãs é feita inteiramente por
hiperligações normais (`<a href="...">`), sem router nem framework.

## Como executar

Não há build nem instalação. Basta abrir `index.html` num browser — funciona a abrir
directamente o ficheiro (duplo-clique) ou a servir a pasta com qualquer servidor estático:

```bash
python3 -m http.server 8000
# depois abrir http://localhost:8000
```

## Estrutura de pastas

```
agroja-app/
├── index.html              → Início (modo Trabalhador) — página de entrada
├── home-produtor.html      → Início (modo Produtor)
├── vagas.html               → Feed de vagas (Trabalhador)
├── detalhes-vaga.html       → Detalhe da vaga + aceitar trabalho (Trabalhador)
├── mensagens.html           → Lista de conversas (Trabalhador)
├── conversa.html            → Chat (Trabalhador)
├── meus-trabalhos.html      → Candidaturas e trabalhos (Trabalhador)
├── perfil.html              → Perfil (Trabalhador)
├── publicar-vaga.html       → Formulário de publicação (Produtor)
├── painel-produtor.html     → Painel com KPIs e vagas activas (Produtor)
├── mensagens-produtor.html  → Lista de conversas (Produtor)
├── conversa-produtor.html   → Chat (Produtor)
├── perfil-produtor.html     → Perfil (Produtor)
├── css/
│   └── style.css            → Estilos partilhados (fora dos utilitários Tailwind)
└── js/
    ├── app.js                → Envio de chat + modais de sucesso
    └── tailwind-config.js    → Tokens de design partilhados (cores, tipografia, espaçamento)
```

## Como funciona a alternância de papel (Trabalhador / Produtor)

Não há sessão nem JavaScript de estado: o botão de "trocar de modo" no cabeçalho do
Início é simplesmente uma hiperligação entre `index.html` (Trabalhador) e
`home-produtor.html` (Produtor). Cada papel tem o seu próprio conjunto de páginas e a
sua própria barra de navegação inferior — por isso cada ficheiro funciona sozinho, sem
depender de estado carregado por JavaScript.

## Dependências externas (via CDN)

Por serem apenas HTML/CSS "puro" sem build step, três recursos continuam a vir de fora
e por isso precisam de ligação à internet para o visual ficar 100% fiel:

- **Tailwind CSS** (`cdn.tailwindcss.com`) — gera as classes utilitárias em tempo real
- **Google Fonts — Inter**
- **Google Fonts — Material Symbols** (ícones)

Sem internet, a página continua a funcionar e a navegar normalmente, mas cai para a
fonte e ícones por defeito do sistema. Se precisares de uma versão 100% offline (fontes
e Tailwind compilado localmente, sem qualquer CDN), este é o próximo passo natural — é
só pedir.

## Dados

Todo o conteúdo (vagas, mensagens, perfis, métricas) é estático/fictício, para
demonstrar o fluxo. Não há backend, base de dados nem autenticação — ver o PRD
(`PRD-AgroJa.pdf`) para o roteiro de como isto evolui para uma versão com API real.
