(function(){function e(e,t){if(!(!e||!/^\d+(\.\d+)?(px)?$/.test(String(e))))return{height:e,isAuto:!1};let n=t?Math.round(t.getBoundingClientRect().top):0;return{height:Math.max(320,window.innerHeight-n-10),isAuto:!0}}function t(e,t){let n=()=>{let t=typeof e.get==`function`?e.get(0):null,n=t?Math.round(t.getBoundingClientRect().top):0,r=Math.max(320,window.innerHeight-n-8);e.height(r),e.trigger(`resize`)};t.bind(`load init open`,n),window.addEventListener(`resize`,n),setTimeout(n,300)}function n(e,t){let n=()=>{let e=Array.from(document.querySelectorAll(`.elfinder`)),t=null,n=0;return e.forEach(e=>{let r=window.getComputedStyle(e);if(r.display===`none`||r.visibility===`hidden`)return;let i=e.getBoundingClientRect(),a=i.width*i.height;i.width<200||i.height<120||a<=0||a>n&&(n=a,t=i)}),t},r=e=>{if(!n())return;let t=e.getBoundingClientRect(),r=e.querySelector(`.ui-dialog-titlebar`),i=Math.max(28,Math.round(r?.getBoundingClientRect().height??36)),a=window.getComputedStyle(e),o=Number.parseFloat(e.style.top||a.top),s=Number.parseFloat(e.style.left||a.left),c=document.getElementById(`wpadminbar`),l=c?Math.max(0,Math.round(c.getBoundingClientRect().bottom)):0,u=window.innerWidth||document.documentElement.clientWidth,d=window.innerHeight||document.documentElement.clientHeight,f=Number.isFinite(o)?o:l+36,p=Number.isFinite(s)?s:36,m=l+8,h=Math.max(m,d-i-8),g=Math.max(180,Math.round(Math.min(t.width||640,u))),_=Math.max(8,u-g-8),v=Math.min(Math.max(f,m),h),y=Math.min(Math.max(p,8),_);e.style.setProperty(`top`,`${Math.round(v)}px`,`important`),e.style.setProperty(`left`,`${Math.round(y)}px`,`important`),e.style.removeProperty(`right`),e.style.removeProperty(`bottom`)};e(`.elfinder-quicklook`).each(function(){let t=this.getAttribute(`data-elfinder-elevated`)===`1`,n=e(this);n.parent()[0]!==document.body&&n.appendTo(`body`),n.css({position:`fixed`,zIndex:100001}),this.setAttribute(`data-elfinder-elevated`,`1`),t||r(this),typeof n.draggable==`function`&&(n.draggable(`option`,`containment`,!1),n.off(`drag.elfinderElevate stop.elfinderElevate`),n.on(`drag.elfinderElevate stop.elfinderElevate`,()=>{r(this),this.style.setProperty(`z-index`,`100002`,`important`)}))}),(t?t.closest(`.ui-dialog.elfinder-dialog`):e(`.ui-dialog.elfinder-dialog`)).each(function(){let t=e(this);if(t.hasClass(`elfinder-dialog-minimized`))return;t.parent()[0]!==document.body&&t.appendTo(`body`),t.css({position:`fixed`,zIndex:1e5}),t.attr(`data-elfinder-elevated`,`1`);let n=this.classList.contains(`elfinder-dialog-edit`)||this.classList.contains(`elfinder-to-editing`);if(n&&(r(this),this.setAttribute(`data-elfinder-initial-pos`,`1`),this.getAttribute(`data-elfinder-reclamp-bound`)!==`1`)){let e=!1,n=()=>{e||(e=!0,r(this),queueMicrotask(()=>{e=!1}))},i=new MutationObserver(n);i.observe(this,{attributes:!0,attributeFilter:[`style`]}),window.addEventListener(`resize`,n),t.one(`remove`,()=>{i.disconnect(),window.removeEventListener(`resize`,n)}),this.setAttribute(`data-elfinder-reclamp-bound`,`1`)}typeof t.draggable==`function`&&(t.draggable(`option`,`containment`,!1),n&&(t.off(`drag.elfinderElevate stop.elfinderElevate`),t.on(`drag.elfinderElevate stop.elfinderElevate`,()=>{r(this),this.style.setProperty(`z-index`,`100002`,`important`)})));let i=this.querySelector(`.ui-dialog-titlebar-close .ui-icon`);if(i&&(i.style.setProperty(`width`,`16px`,`important`),i.style.setProperty(`height`,`16px`,`important`)),this.classList.contains(`elfinder-dialog-preference`)){let e=this.querySelector(`.ui-dialog-titlebar-close`);e&&e.style.setProperty(`display`,`block`,`important`)}}),e(`.ui-widget-overlay`).filter(function(){let t=e(this);return t.nextAll(`.ui-dialog.elfinder-dialog`).length>0||t.prevAll(`.ui-dialog.elfinder-dialog`).length>0}).each(function(){let t=e(this);t.parent()[0]!==document.body&&t.appendTo(`body`),t.css({position:`fixed`,zIndex:99999}),t.attr(`data-elfinder-elevated`,`1`)})}function r(){let e=`elfinder-dock-style`;if(document.getElementById(e))return;let t=document.createElement(`style`);t.id=e,t.textContent=`
    .elfinder-bottomtray {
      position: fixed !important;
      bottom: 0 !important;
      left: 0 !important;
      right: 0 !important;
      width: 100% !important;
      max-width: none !important;
      height: auto !important;
      background: rgba(22, 22, 24, 0.88) !important;
      backdrop-filter: blur(14px) saturate(180%);
      -webkit-backdrop-filter: blur(14px) saturate(180%);
      display: flex !important;
      flex-direction: row;
      align-items: center;
      flex-wrap: wrap;
      gap: 8px;
      padding: 6px 16px 8px;
      z-index: 100001 !important;
      box-sizing: border-box !important;
      box-shadow: 0 -1px 0 rgba(255,255,255,0.06), 0 -4px 24px rgba(0,0,0,0.6);
      border-top: 1px solid rgba(255,255,255,0.07);
    }
    .elfinder-bottomtray:empty { display: none !important; }
    .elfinder-bottomtray .elfinder-dialog-minimized {
      position: static !important;
      float: none !important;
      display: flex !important;
      align-items: center;
      width: auto !important;
      max-width: 200px !important;
      min-width: 90px;
      height: 34px !important;
      padding: 0 !important;
      margin: 0 !important;
      background: rgba(255,255,255,0.10) !important;
      border: 1px solid rgba(255,255,255,0.18) !important;
      border-radius: 999px !important;
      box-shadow: 0 1px 4px rgba(0,0,0,0.35), inset 0 1px 0 rgba(255,255,255,0.12) !important;
      overflow: hidden;
      cursor: pointer;
      transition: background 0.15s ease, box-shadow 0.15s ease, transform 0.1s ease;
    }
    .elfinder-bottomtray .elfinder-dialog-minimized:hover {
      background: rgba(255,255,255,0.18) !important;
      box-shadow: 0 2px 8px rgba(0,0,0,0.5), inset 0 1px 0 rgba(255,255,255,0.18) !important;
      transform: translateY(-2px);
    }
    .elfinder-bottomtray .elfinder-dialog-minimized:active { transform: translateY(0); }
    .elfinder-bottomtray .elfinder-dialog-minimized .ui-dialog-titlebar {
      display: flex !important;
      align-items: center;
      width: 100% !important;
      height: 34px !important;
      padding: 0 10px 0 14px !important;
      margin: 0 !important;
      background: transparent !important;
      border: none !important;
      border-radius: 0 !important;
      gap: 6px;
    }
    .elfinder-bottomtray .elfinder-dialog-minimized .ui-dialog-title,
    .elfinder-bottomtray .elfinder-dialog-minimized .elfinder-dialog-title {
      order: 1;
      color: rgba(255,255,255,0.92) !important;
      font-size: 11.5px !important;
      font-weight: 500 !important;
      letter-spacing: 0.01em;
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
      flex: 1;
      min-width: 0;
      line-height: 34px !important;
      text-shadow: none !important;
    }
    .elfinder-bottomtray .elfinder-dialog-minimized .ui-dialog-titlebar-close {
      order: 2;
      flex-shrink: 0;
      display: flex !important;
      align-items: center !important;
      justify-content: center !important;
      width: 18px !important;
      height: 18px !important;
      min-width: 18px !important;
      min-height: 18px !important;
      padding: 0 !important;
      margin: 0 !important;
      background: rgba(255,255,255,0.12) !important;
      border: none !important;
      border-radius: 50% !important;
      opacity: 0.7;
      transition: background 0.12s, opacity 0.12s;
    }
    .elfinder-bottomtray .elfinder-dialog-minimized .ui-dialog-titlebar-close:hover {
      background: rgba(255, 75, 75, 0.75) !important;
      opacity: 1;
    }
    .elfinder-bottomtray .elfinder-dialog-minimized .ui-dialog-titlebar-close .ui-icon {
      width: 10px !important;
      height: 10px !important;
    }
  `,document.head.appendChild(t)}function i(){let e=document.querySelector(`.elfinder-bottomtray`);!e||e.parentElement===document.body||document.body.appendChild(e)}function a(){if(i(),document.querySelector(`.elfinder-bottomtray`)?.parentElement===document.body)return;let e=document.querySelector(`.elfinder`);if(!e)return;let t=new MutationObserver(()=>{document.querySelector(`.elfinder-bottomtray`)&&(t.disconnect(),i())});t.observe(e,{childList:!0})}function o(){let e=window.elFinder;e&&(e.prototype.commands.emailto=function(){let{fm:e}=this,t=e=>e.filter(e=>e.mime!==`directory`);this.exec=t=>{let n=String(e.url(t[0],0)),r=n.split(`/`).pop()??``,i=prompt(`Please enter mail address`);if(i!=null){if(!/^(([^<>()[\].,;:\s@"]+(\.[^<>()[\].,;:\s@"]+)*)|(".+"))@(([^<>()[\].,;:\s@"]+\.)+[^<>()[\].,;:\s@"]{2,})$/i.test(i)){alert(`Please enter a valid email address`);return}window.open(`mailto:${i}?subject=${encodeURIComponent(r)}&body=${encodeURIComponent(n)}`)}},this.getstate=e=>{let n=this.files(e);return n.length&&t(n).length===n.length?0:-1}})}function s(e){e.ui?.dialog?.prototype?.options&&(e.ui.dialog.prototype.options.appendTo=`body`)}function c(e){let t=e;if(typeof t.toFront==`function`){let n=t.toFront.bind(e);t.toFront=function(e){n(e);let t=e?.[0]??(e instanceof HTMLElement?e:void 0);t?.parentElement===document.body&&(t.style.zIndex=`100000`)}}}function l(e,t){let r=e.dialog.bind(e);e.dialog=function(i,a){let o=a&&typeof a==`object`&&!Array.isArray(a)?{...a,appendTo:`body`,zIndex:1e5}:{appendTo:`body`,zIndex:1e5},s=r(i,o);return s.closest(`.ui-dialog`).data(`elfinder-fm`,e),n(t,s),s}}var u=[{name:`C`,mode:`clike`,ext:[`c`,`h`,`ino`],mime:`text/x-csrc`},{name:`C++`,mode:`clike`,ext:[`cpp`,`cxx`,`cc`,`hpp`],mime:`text/x-c++src`},{name:`Java`,mode:`clike`,ext:[`java`],mime:`text/x-java`},{name:`C#`,mode:`clike`,ext:[`cs`],mime:`text/x-csharp`},{name:`CSS`,mode:`css`,ext:[`css`],mime:`text/css`},{name:`SCSS`,mode:`css`,ext:[`scss`],mime:`text/x-scss`},{name:`SASS`,mode:`sass`,ext:[`sass`],mime:`text/x-sass`},{name:`Diff`,mode:`diff`,ext:[`diff`,`patch`],mime:`text/x-diff`},{name:`HTML`,mode:`htmlmixed`,ext:[`html`,`htm`],mime:`text/html`},{name:`HTTP`,mode:`http`,mime:`message/http`},{name:`JavaScript`,mode:`javascript`,ext:[`js`,`mjs`],mime:`text/javascript`,mimes:[`text/javascript`,`application/javascript`,`application/x-javascript`]},{name:`JSON`,mode:`javascript`,ext:[`json`,`map`],mime:`application/json`},{name:`JSX`,mode:`jsx`,ext:[`jsx`],mime:`text/jsx`},{name:`Markdown`,mode:`markdown`,ext:[`md`,`markdown`],mime:`text/x-markdown`},{name:`GFM`,mode:`gfm`,mime:`text/x-gfm`},{name:`Nginx`,mode:`nginx`,mime:`text/x-nginx-conf`},{name:`PHP`,mode:`php`,ext:[`php`,`php3`,`php4`,`php5`,`php7`,`phtml`],mime:`application/x-httpd-php`,mimes:[`application/x-httpd-php`,`application/x-httpd-php-open`,`text/x-php`]},{name:`Shell`,mode:`shell`,ext:[`sh`,`bash`],mime:`application/x-sh`},{name:`SQL`,mode:`sql`,ext:[`sql`],mime:`text/x-sql`},{name:`XML`,mode:`xml`,ext:[`xml`,`svg`,`xsl`],mime:`application/xml`},{name:`YAML`,mode:`yaml`,ext:[`yml`,`yaml`],mime:`text/x-yaml`}],d=/\/wp-includes\/js\/codemirror\//;function f(e){let t=window,n=t.wp?.CodeMirror;if(!n)return;t.CodeMirror=n,n.modeInfo||(n.modeInfo=u,n.findModeByMIME=e=>{let t=e.toLowerCase();return u.find(e=>e.mime===t||e.mimes?.includes(t))??null},n.findModeByExtension=e=>{let t=e.toLowerCase();return u.find(e=>e.ext?.includes(t))??null},n.findModeByName=e=>{let t=e.toLowerCase();return u.find(e=>e.name.toLowerCase()===t)??null}),typeof n.autoLoadMode!=`function`&&(n.autoLoadMode=()=>{},n.requireMode=(e,t)=>t?.());let r=e,i=r.loadScript?.bind(e);i&&(r.loadScript=(e,n,...r)=>{if(Array.isArray(e)&&e.every(e=>d.test(String(e)))){n?.(t.CodeMirror);return}return i(e,n,...r)});let a=r.loadCss?.bind(e);a&&(r.loadCss=e=>{if(!(typeof e==`string`&&d.test(e)))return a(e)})}var p=Symbol(`bitfmPatched`);function m(e){let t=e,n=t.fn.elfinderdialog;if(n&&!n[p]){let e=function(e,t,...r){let i=t;if(typeof e==`string`&&i==null){let e=this;i=e.data?.(`elfinder-fm`)||e.closest?.(`.ui-dialog`)?.data?.(`elfinder-fm`)}return n.call(this,e,i,...r)};e.defaults=n.defaults,e[p]=!0,t.fn.elfinderdialog=e}}var h=window;h.jQuery(document).ready(()=>{let i=h.jQuery,u=h.bitapps_fm;o(),s(i);let d=i(`#file-manager`),p=d.get?.(0),{height:g,isAuto:_}=e(u.options.height,p),v=d.elfinder({url:u.ajaxURL,themes:u.options.themes,theme:u.options.theme,cssAutoLoad:u.options.cssAutoLoad,contextmenu:u.options.contextmenu,customData:{action:u.action,nonce:u.nonce},lang:u.options.lang,requestType:u.options.requestType,width:u.options.width,height:g,commandsOptions:u.options.commandsOptions,commands:u.options.commands,disabled:u.options.disabled,rememberLastDir:u.options.rememberLastDir,reloadClearHistory:u.options.reloadClearHistory,defaultView:u.options.defaultView,ui:u.options.ui,sortOrder:u.options.sortOrder,sortStickFolders:u.options.sortStickFolders,dragUploadAllow:u.options.dragUploadAllow,fileModeStyle:u.options.fileModeStyle,resizable:u.options.resizable,cdns:u.options.cdns,handlers:{dblclick(){let e=u?.options?.disabled??[];if(e.includes(`dblclick`)||e.includes(`download`)||e.includes(`get`))return!1}}})?.[0]?.elfinder;v&&(c(v),l(v,i),m(i),f(v),n(i),r(),v.bind(`load`,a),_&&t(d,v))})})();