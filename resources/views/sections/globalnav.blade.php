<nav class="modal -globalnav" data-modal="globalnav">
  <div class="modal-backdrop" data-toggle="globalnav"></div>
  <div class="modal-wrapper">
    <div class="modal-header">
      <a href="/" title="GK Empreendimentos">
        <img src="@asset('images/logo.png')" width="56" height="40" alt="GK Empreendimentos">
      </a>

      <button class="button -icon" data-toggle="globalnav" aria-label="Abrir menu">
        <i aria-hidden="true" class="material-symbols-outlined">
          close
        </i>
      </button>
    </div>

    <div class="modal-content">
      <ul class="globalnav-menu">
        <li>
          <span>Empreendimentos</span>
          <ul>
            @foreach ($enterprises as $item)
            <li><a href="{{$item['permalink']}}">{{$item['title']}}</a></li>
            @endforeach
          </ul>
        </li>
      </ul>
    </div>
    
    <div class="modal-footer">
      <a href="https://api.whatsapp.com/send?text=https://tabelas.gkempreendimentos.com.br/" class="button -full -whatsapp" target="_blank" rel="noopener noreferrer">
        <svg width="24" height="24">
          <use xlink:href="#icon-whatsapp"></use>
        </svg>
        <span>Compartilhe no WhatsApp</span>
      </a>
      <a href="https://gkempreendimentos.com.br/" class="button -full -site" target="_blank" rel="noopener noreferrer">
        <span>Site da GK Empreendimentos</span>
        <span class="material-symbols-outlined">
          arrow_forward
        </span>
      </a>
    </div>
  </div>
</nav>