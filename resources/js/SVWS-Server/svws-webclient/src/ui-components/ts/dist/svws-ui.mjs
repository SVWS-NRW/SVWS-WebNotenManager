import { defineComponent as C, openBlock as m, createElementBlock as b, normalizeClass as j, renderSlot as P, createElementVNode as h, computed as S, withDirectives as te, isRef as ot, vModelCheckbox as Cn, unref as B, createVNode as x, withCtx as I, createBlock as R, createCommentVNode as L, cloneVNode as ba, h as N, Fragment as Z, inject as $e, provide as Ee, ref as k, onMounted as K, watchEffect as he, watch as J, onUnmounted as Be, Teleport as ya, reactive as Ft, nextTick as ye, resolveComponent as yt, useSlots as Pn, createTextVNode as Ne, toDisplayString as q, withModifiers as de, renderList as ue, withKeys as be, vShow as pe, pushScopeId as wa, popScopeId as _a, normalizeStyle as On, vModelRadio as $a, vModelSelect as Sa, onUpdated as ka, useCssVars as xa, toRefs as An, Transition as Ia, onBeforeUnmount as Ut, createSlots as Ca, createStaticVNode as Pa, toRef as ge } from "vue";
const Oa = ["disabled"], lt = /* @__PURE__ */ C({
  __name: "Button",
  props: {
    type: { default: "primary" },
    disabled: { type: Boolean, default: !1 },
    dropdownAction: { type: Boolean, default: !1 }
  },
  emits: ["click"],
  setup(e, { emit: t }) {
    function n(o) {
      e.disabled || t("click", o);
    }
    return (o, a) => (m(), b("button", {
      class: j(["button", {
        "button--primary": e.type === "primary",
        "button--secondary": e.type === "secondary",
        "button--danger": e.type === "danger",
        "button--transparent": e.type === "transparent",
        "button--dropdown-action": e.dropdownAction === !0
      }]),
      disabled: e.disabled,
      onClick: n
    }, [
      P(o.$slots, "default")
    ], 10, Oa));
  }
});
const Aa = {
  preserveAspectRatio: "xMidYMid meet",
  viewBox: "0 0 24 24",
  width: "1.2em",
  height: "1.2em"
}, Ma = /* @__PURE__ */ h("path", {
  fill: "currentColor",
  d: "M3 12h4v9H3v-9zm14-4h4v13h-4V8zm-7-6h4v19h-4V2z"
}, null, -1), Da = [
  Ma
];
function Ea(e, t) {
  return m(), b("svg", Aa, Da);
}
const rt = { name: "ri-bar-chart-fill", render: Ea };
const Pe = (e, t) => {
  const n = e.__vccOpts || e;
  for (const [o, a] of t)
    n[o] = a;
  return n;
}, Ba = {}, Ta = { class: "icon" };
function Ra(e, t) {
  return m(), b("span", Ta, [
    P(e.$slots, "default")
  ]);
}
const ie = /* @__PURE__ */ Pe(Ba, [["render", Ra]]), La = {
  preserveAspectRatio: "xMidYMid meet",
  viewBox: "0 0 24 24",
  width: "1.2em",
  height: "1.2em"
}, za = /* @__PURE__ */ h("path", {
  fill: "currentColor",
  d: "m10 15.172l9.192-9.193l1.415 1.414L10 18l-6.364-6.364l1.414-1.414z"
}, null, -1), Na = [
  za
];
function ja(e, t) {
  return m(), b("svg", La, Na);
}
const Va = { name: "ri-check-line", render: ja }, Fa = ["value", "disabled"], Ua = { class: "checkbox--indicator" }, Ha = { class: "checkbox--label" }, St = /* @__PURE__ */ C({
  __name: "Checkbox",
  props: {
    value: { type: [String, Number, Boolean], default: "" },
    modelValue: { type: [Boolean, Array] },
    statistics: { type: Boolean, default: !1 },
    disabled: { type: Boolean, default: !1 }
  },
  emits: ["update:modelValue"],
  setup(e, { emit: t }) {
    const n = S({
      get() {
        return e.modelValue;
      },
      set(o) {
        t("update:modelValue", o);
      }
    });
    return (o, a) => {
      const l = Va, r = ie, s = rt;
      return m(), b("label", {
        class: j(["checkbox", {
          "checkbox--disabled": e.disabled,
          "checkbox--statistics": e.statistics
        }])
      }, [
        te(h("input", {
          "onUpdate:modelValue": a[0] || (a[0] = (i) => ot(n) ? n.value = i : null),
          class: "checkbox--control",
          type: "checkbox",
          value: e.value,
          disabled: e.disabled
        }, null, 8, Fa), [
          [Cn, B(n)]
        ]),
        h("span", Ua, [
          x(r, null, {
            default: I(() => [
              x(l)
            ]),
            _: 1
          })
        ]),
        h("span", Ha, [
          P(o.$slots, "default"),
          e.statistics ? (m(), R(s, {
            key: 0,
            class: "ml-2"
          })) : L("", !0)
        ])
      ], 2);
    };
  }
});
const Wa = {
  preserveAspectRatio: "xMidYMid meet",
  viewBox: "0 0 24 24",
  width: "1.2em",
  height: "1.2em"
}, qa = /* @__PURE__ */ h("path", {
  fill: "currentColor",
  d: "m12 13.172l4.95-4.95l1.414 1.414L12 16L5.636 9.636L7.05 8.222z"
}, null, -1), Ka = [
  qa
];
function Ya(e, t) {
  return m(), b("svg", Wa, Ka);
}
const st = { name: "ri-arrow-down-s-line", render: Ya }, Ga = {
  preserveAspectRatio: "xMidYMid meet",
  viewBox: "0 0 24 24",
  width: "1.2em",
  height: "1.2em"
}, Za = /* @__PURE__ */ h("path", {
  fill: "currentColor",
  d: "m12 10.828l-4.95 4.95l-1.414-1.414L12 8l6.364 6.364l-1.414 1.414z"
}, null, -1), Xa = [
  Za
];
function Qa(e, t) {
  return m(), b("svg", Ga, Xa);
}
const kt = { name: "ri-arrow-up-s-line", render: Qa };
function Me(e, t, ...n) {
  if (e in t) {
    let a = t[e];
    return typeof a == "function" ? a(...n) : a;
  }
  let o = new Error(`Tried to handle "${e}" but there is no handler defined. Only defined handlers are: ${Object.keys(t).map((a) => `"${a}"`).join(", ")}.`);
  throw Error.captureStackTrace && Error.captureStackTrace(o, Me), o;
}
var je = ((e) => (e[e.None = 0] = "None", e[e.RenderStrategy = 1] = "RenderStrategy", e[e.Static = 2] = "Static", e))(je || {}), Ja = ((e) => (e[e.Unmount = 0] = "Unmount", e[e.Hidden = 1] = "Hidden", e))(Ja || {});
function Y({ visible: e = !0, features: t = 0, ourProps: n, theirProps: o, ...a }) {
  var l;
  let r = eo(o, n), s = Object.assign(a, { props: r });
  if (e || t & 2 && r.static)
    return Ot(s);
  if (t & 1) {
    let i = (l = r.unmount) == null || l ? 0 : 1;
    return Me(i, { [0]() {
      return null;
    }, [1]() {
      return Ot({ ...a, props: { ...r, hidden: !0, style: { display: "none" } } });
    } });
  }
  return Ot(s);
}
function Ot({ props: e, attrs: t, slots: n, slot: o, name: a }) {
  var l;
  let { as: r, ...s } = Dn(e, ["unmount", "static"]), i = (l = n.default) == null ? void 0 : l.call(n, o), u = {};
  if (r === "template") {
    if (i = Mn(i), Object.keys(s).length > 0 || Object.keys(t).length > 0) {
      let [c, ...d] = i != null ? i : [];
      if (!to(c) || d.length > 0)
        throw new Error(['Passing props on "template"!', "", `The current component <${a} /> is rendering a "template".`, "However we need to passthrough the following props:", Object.keys(s).concat(Object.keys(t)).sort((v, p) => v.localeCompare(p)).map((v) => `  - ${v}`).join(`
`), "", "You can apply a few solutions:", ['Add an `as="..."` prop, to ensure that we render an actual element instead of a "template".', "Render a single element as the child so that we can forward the props onto that element."].map((v) => `  - ${v}`).join(`
`)].join(`
`));
      return ba(c, Object.assign({}, s, u));
    }
    return Array.isArray(i) && i.length === 1 ? i[0] : i;
  }
  return N(r, Object.assign({}, s, u), i);
}
function Mn(e) {
  return e.flatMap((t) => t.type === Z ? Mn(t.children) : [t]);
}
function eo(...e) {
  if (e.length === 0)
    return {};
  if (e.length === 1)
    return e[0];
  let t = {}, n = {};
  for (let o of e)
    for (let a in o)
      a.startsWith("on") && typeof o[a] == "function" ? (n[a] != null || (n[a] = []), n[a].push(o[a])) : t[a] = o[a];
  if (t.disabled || t["aria-disabled"])
    return Object.assign(t, Object.fromEntries(Object.keys(n).map((o) => [o, void 0])));
  for (let o in n)
    Object.assign(t, { [o](a, ...l) {
      let r = n[o];
      for (let s of r) {
        if (a != null && a.defaultPrevented)
          return;
        s(a, ...l);
      }
    } });
  return t;
}
function Dn(e, t = []) {
  let n = Object.assign({}, e);
  for (let o of t)
    o in n && delete n[o];
  return n;
}
function to(e) {
  return e == null ? !1 : typeof e.type == "string" || typeof e.type == "object" || typeof e.type == "function";
}
let no = 0;
function ao() {
  return ++no;
}
function Se() {
  return ao();
}
var F = ((e) => (e.Space = " ", e.Enter = "Enter", e.Escape = "Escape", e.Backspace = "Backspace", e.Delete = "Delete", e.ArrowLeft = "ArrowLeft", e.ArrowUp = "ArrowUp", e.ArrowRight = "ArrowRight", e.ArrowDown = "ArrowDown", e.Home = "Home", e.End = "End", e.PageUp = "PageUp", e.PageDown = "PageDown", e.Tab = "Tab", e))(F || {});
function oo(e) {
  throw new Error("Unexpected object: " + e);
}
var ce = ((e) => (e[e.First = 0] = "First", e[e.Previous = 1] = "Previous", e[e.Next = 2] = "Next", e[e.Last = 3] = "Last", e[e.Specific = 4] = "Specific", e[e.Nothing = 5] = "Nothing", e))(ce || {});
function lo(e, t) {
  let n = t.resolveItems();
  if (n.length <= 0)
    return null;
  let o = t.resolveActiveIndex(), a = o != null ? o : -1, l = (() => {
    switch (e.focus) {
      case 0:
        return n.findIndex((r) => !t.resolveDisabled(r));
      case 1: {
        let r = n.slice().reverse().findIndex((s, i, u) => a !== -1 && u.length - i - 1 >= a ? !1 : !t.resolveDisabled(s));
        return r === -1 ? r : n.length - 1 - r;
      }
      case 2:
        return n.findIndex((r, s) => s <= a ? !1 : !t.resolveDisabled(r));
      case 3: {
        let r = n.slice().reverse().findIndex((s) => !t.resolveDisabled(s));
        return r === -1 ? r : n.length - 1 - r;
      }
      case 4:
        return n.findIndex((r) => t.resolveId(r) === e.id);
      case 5:
        return null;
      default:
        oo(e);
    }
  })();
  return l === -1 ? o : l;
}
function V(e) {
  var t;
  return e == null || e.value == null ? null : (t = e.value.$el) != null ? t : e.value;
}
let En = Symbol("Context");
var qe = ((e) => (e[e.Open = 0] = "Open", e[e.Closed = 1] = "Closed", e))(qe || {});
function Bn() {
  return $e(En, null);
}
function ro(e) {
  Ee(En, e);
}
function pn(e, t) {
  if (e)
    return e;
  let n = t != null ? t : "button";
  if (typeof n == "string" && n.toLowerCase() === "button")
    return "button";
}
function Tn(e, t) {
  let n = k(pn(e.value.type, e.value.as));
  return K(() => {
    n.value = pn(e.value.type, e.value.as);
  }), he(() => {
    var o;
    n.value || !V(t) || V(t) instanceof HTMLButtonElement && !((o = V(t)) != null && o.hasAttribute("type")) && (n.value = "button");
  }), n;
}
function Ve(e) {
  if (typeof window > "u")
    return null;
  if (e instanceof Node)
    return e.ownerDocument;
  if (e != null && e.hasOwnProperty("value")) {
    let t = V(e);
    if (t)
      return t.ownerDocument;
  }
  return document;
}
function so({ container: e, accept: t, walk: n, enabled: o }) {
  he(() => {
    let a = e.value;
    if (!a || o !== void 0 && !o.value)
      return;
    let l = Ve(e);
    if (!l)
      return;
    let r = Object.assign((i) => t(i), { acceptNode: t }), s = l.createTreeWalker(a, NodeFilter.SHOW_ELEMENT, r, !1);
    for (; s.nextNode(); )
      n(s.currentNode);
  });
}
let Dt = ["[contentEditable=true]", "[tabindex]", "a[href]", "area[href]", "button:not([disabled])", "iframe", "input:not([disabled])", "select:not([disabled])", "textarea:not([disabled])"].map((e) => `${e}:not([tabindex='-1'])`).join(",");
var le = ((e) => (e[e.First = 1] = "First", e[e.Previous = 2] = "Previous", e[e.Next = 4] = "Next", e[e.Last = 8] = "Last", e[e.WrapAround = 16] = "WrapAround", e[e.NoScroll = 32] = "NoScroll", e))(le || {}), Rn = ((e) => (e[e.Error = 0] = "Error", e[e.Overflow = 1] = "Overflow", e[e.Success = 2] = "Success", e[e.Underflow = 3] = "Underflow", e))(Rn || {}), io = ((e) => (e[e.Previous = -1] = "Previous", e[e.Next = 1] = "Next", e))(io || {});
function uo(e = document.body) {
  return e == null ? [] : Array.from(e.querySelectorAll(Dt));
}
var Ht = ((e) => (e[e.Strict = 0] = "Strict", e[e.Loose = 1] = "Loose", e))(Ht || {});
function Ln(e, t = 0) {
  var n;
  return e === ((n = Ve(e)) == null ? void 0 : n.body) ? !1 : Me(t, { [0]() {
    return e.matches(Dt);
  }, [1]() {
    let o = e;
    for (; o !== null; ) {
      if (o.matches(Dt))
        return !0;
      o = o.parentElement;
    }
    return !1;
  } });
}
function Qe(e) {
  e == null || e.focus({ preventScroll: !0 });
}
let co = ["textarea", "input"].join(",");
function po(e) {
  var t, n;
  return (n = (t = e == null ? void 0 : e.matches) == null ? void 0 : t.call(e, co)) != null ? n : !1;
}
function zn(e, t = (n) => n) {
  return e.slice().sort((n, o) => {
    let a = t(n), l = t(o);
    if (a === null || l === null)
      return 0;
    let r = a.compareDocumentPosition(l);
    return r & Node.DOCUMENT_POSITION_FOLLOWING ? -1 : r & Node.DOCUMENT_POSITION_PRECEDING ? 1 : 0;
  });
}
function Ae(e, t, n = !0) {
  var o;
  let a = (o = Array.isArray(e) ? e.length > 0 ? e[0].ownerDocument : document : e == null ? void 0 : e.ownerDocument) != null ? o : document, l = Array.isArray(e) ? n ? zn(e) : e : uo(e), r = a.activeElement, s = (() => {
    if (t & 5)
      return 1;
    if (t & 10)
      return -1;
    throw new Error("Missing Focus.First, Focus.Previous, Focus.Next or Focus.Last");
  })(), i = (() => {
    if (t & 1)
      return 0;
    if (t & 2)
      return Math.max(0, l.indexOf(r)) - 1;
    if (t & 4)
      return Math.max(0, l.indexOf(r)) + 1;
    if (t & 8)
      return l.length - 1;
    throw new Error("Missing Focus.First, Focus.Previous, Focus.Next or Focus.Last");
  })(), u = t & 32 ? { preventScroll: !0 } : {}, c = 0, d = l.length, v;
  do {
    if (c >= d || c + d <= 0)
      return 0;
    let p = i + c;
    if (t & 16)
      p = (p + d) % d;
    else {
      if (p < 0)
        return 3;
      if (p >= d)
        return 1;
    }
    v = l[p], v == null || v.focus(u), c += s;
  } while (v !== a.activeElement);
  return v.hasAttribute("tabindex") || v.setAttribute("tabindex", "0"), t & 6 && po(v) && v.select(), 2;
}
function Et(e, t, n) {
  typeof window < "u" && he((o) => {
    window.addEventListener(e, t, n), o(() => window.removeEventListener(e, t, n));
  });
}
function Nn(e, t, n = S(() => !0)) {
  function o(a, l) {
    if (!n.value || a.defaultPrevented)
      return;
    let r = l(a);
    if (r === null || !r.ownerDocument.documentElement.contains(r))
      return;
    let s = function i(u) {
      return typeof u == "function" ? i(u()) : Array.isArray(u) || u instanceof Set ? u : [u];
    }(e);
    for (let i of s) {
      if (i === null)
        continue;
      let u = i instanceof HTMLElement ? i : V(i);
      if (u != null && u.contains(r))
        return;
    }
    return !Ln(r, Ht.Loose) && r.tabIndex !== -1 && a.preventDefault(), t(a, r);
  }
  Et("click", (a) => o(a, (l) => l.target), !0), Et("blur", (a) => o(a, () => window.document.activeElement instanceof HTMLIFrameElement ? window.document.activeElement : null), !0);
}
var et = ((e) => (e[e.None = 1] = "None", e[e.Focusable = 2] = "Focusable", e[e.Hidden = 4] = "Hidden", e))(et || {});
let $t = C({ name: "Hidden", props: { as: { type: [Object, String], default: "div" }, features: { type: Number, default: 1 } }, setup(e, { slots: t, attrs: n }) {
  return () => {
    let { features: o, ...a } = e, l = { "aria-hidden": (o & 2) === 2 ? !0 : void 0, style: { position: "absolute", width: 1, height: 1, padding: 0, margin: -1, overflow: "hidden", clip: "rect(0, 0, 0, 0)", whiteSpace: "nowrap", borderWidth: "0", ...(o & 4) === 4 && (o & 2) !== 2 && { display: "none" } } };
    return Y({ ourProps: l, theirProps: a, slot: {}, attrs: n, slots: t, name: "Hidden" });
  };
} });
var Bt = ((e) => (e[e.Forwards = 0] = "Forwards", e[e.Backwards = 1] = "Backwards", e))(Bt || {});
function fo() {
  let e = k(0);
  return Et("keydown", (t) => {
    t.key === "Tab" && (e.value = t.shiftKey ? 1 : 0);
  }), e;
}
function jn(e, t, n, o) {
  typeof window < "u" && he((a) => {
    e = e != null ? e : window, e.addEventListener(t, n, o), a(() => e.removeEventListener(t, n, o));
  });
}
var Vn = ((e) => (e[e.None = 1] = "None", e[e.InitialFocus = 2] = "InitialFocus", e[e.TabLock = 4] = "TabLock", e[e.FocusLock = 8] = "FocusLock", e[e.RestoreFocus = 16] = "RestoreFocus", e[e.All = 30] = "All", e))(Vn || {});
let Ze = Object.assign(C({ name: "FocusTrap", props: { as: { type: [Object, String], default: "div" }, initialFocus: { type: Object, default: null }, features: { type: Number, default: 30 }, containers: { type: Object, default: k(/* @__PURE__ */ new Set()) } }, inheritAttrs: !1, setup(e, { attrs: t, slots: n, expose: o }) {
  let a = k(null);
  o({ el: a, $el: a });
  let l = S(() => Ve(a));
  vo({ ownerDocument: l }, S(() => Boolean(e.features & 16)));
  let r = mo({ ownerDocument: l, container: a, initialFocus: S(() => e.initialFocus) }, S(() => Boolean(e.features & 2)));
  ho({ ownerDocument: l, container: a, containers: e.containers, previousActiveElement: r }, S(() => Boolean(e.features & 8)));
  let s = fo();
  function i() {
    let u = V(a);
    !u || Me(s.value, { [Bt.Forwards]: () => Ae(u, le.First), [Bt.Backwards]: () => Ae(u, le.Last) });
  }
  return () => {
    let u = {}, c = { ref: a }, { features: d, initialFocus: v, containers: p, ...f } = e;
    return N(Z, [Boolean(d & 4) && N($t, { as: "button", type: "button", onFocus: i, features: et.Focusable }), Y({ ourProps: c, theirProps: { ...t, ...f }, slot: u, attrs: t, slots: n, name: "FocusTrap" }), Boolean(d & 4) && N($t, { as: "button", type: "button", onFocus: i, features: et.Focusable })]);
  };
} }), { features: Vn });
function vo({ ownerDocument: e }, t) {
  let n = k(null), o = { value: !1 };
  K(() => {
    J(t, (a, l) => {
      var r;
      a !== l && (!t.value || (o.value = !0, n.value || (n.value = (r = e.value) == null ? void 0 : r.activeElement)));
    }, { immediate: !0 }), J(t, (a, l, r) => {
      a !== l && (!t.value || r(() => {
        o.value !== !1 && (o.value = !1, Qe(n.value), n.value = null);
      }));
    }, { immediate: !0 });
  });
}
function mo({ ownerDocument: e, container: t, initialFocus: n }, o) {
  let a = k(null);
  return K(() => {
    J([t, n, o], (l, r) => {
      if (l.every((i, u) => (r == null ? void 0 : r[u]) === i) || !o.value)
        return;
      let s = V(t);
      !s || requestAnimationFrame(() => {
        var i, u;
        let c = V(n), d = (i = e.value) == null ? void 0 : i.activeElement;
        if (c) {
          if (c === d) {
            a.value = d;
            return;
          }
        } else if (s.contains(d)) {
          a.value = d;
          return;
        }
        c ? Qe(c) : Ae(s, le.First | le.NoScroll) === Rn.Error && console.warn("There are no focusable elements inside the <FocusTrap />"), a.value = (u = e.value) == null ? void 0 : u.activeElement;
      });
    }, { immediate: !0, flush: "post" });
  }), a;
}
function ho({ ownerDocument: e, container: t, containers: n, previousActiveElement: o }, a) {
  var l;
  jn((l = e.value) == null ? void 0 : l.defaultView, "focus", (r) => {
    if (!a.value)
      return;
    let s = new Set(n == null ? void 0 : n.value);
    s.add(t);
    let i = o.value;
    if (!i)
      return;
    let u = r.target;
    u && u instanceof HTMLElement ? go(s, u) ? (o.value = u, Qe(u)) : (r.preventDefault(), r.stopPropagation(), Qe(i)) : Qe(o.value);
  }, !0);
}
function go(e, t) {
  var n;
  for (let o of e)
    if ((n = o.value) != null && n.contains(t))
      return !0;
  return !1;
}
let fn = "body > *", He = /* @__PURE__ */ new Set(), Te = /* @__PURE__ */ new Map();
function vn(e) {
  e.setAttribute("aria-hidden", "true"), e.inert = !0;
}
function mn(e) {
  let t = Te.get(e);
  !t || (t["aria-hidden"] === null ? e.removeAttribute("aria-hidden") : e.setAttribute("aria-hidden", t["aria-hidden"]), e.inert = t.inert);
}
function bo(e, t = k(!0)) {
  he((n) => {
    if (!t.value || !e.value)
      return;
    let o = e.value, a = Ve(o);
    if (a) {
      He.add(o);
      for (let l of Te.keys())
        l.contains(o) && (mn(l), Te.delete(l));
      a.querySelectorAll(fn).forEach((l) => {
        if (l instanceof HTMLElement) {
          for (let r of He)
            if (l.contains(r))
              return;
          He.size === 1 && (Te.set(l, { "aria-hidden": l.getAttribute("aria-hidden"), inert: l.inert }), vn(l));
        }
      }), n(() => {
        if (He.delete(o), He.size > 0)
          a.querySelectorAll(fn).forEach((l) => {
            if (l instanceof HTMLElement && !Te.has(l)) {
              for (let r of He)
                if (l.contains(r))
                  return;
              Te.set(l, { "aria-hidden": l.getAttribute("aria-hidden"), inert: l.inert }), vn(l);
            }
          });
        else
          for (let l of Te.keys())
            mn(l), Te.delete(l);
      });
    }
  });
}
let Fn = Symbol("ForcePortalRootContext");
function yo() {
  return $e(Fn, !1);
}
let Tt = C({ name: "ForcePortalRoot", props: { as: { type: [Object, String], default: "template" }, force: { type: Boolean, default: !1 } }, setup(e, { slots: t, attrs: n }) {
  return Ee(Fn, e.force), () => {
    let { force: o, ...a } = e;
    return Y({ theirProps: a, ourProps: {}, slot: {}, slots: t, attrs: n, name: "ForcePortalRoot" });
  };
} });
function wo(e) {
  let t = Ve(e);
  if (!t) {
    if (e === null)
      return null;
    throw new Error(`[Headless UI]: Cannot find ownerDocument for contextElement: ${e}`);
  }
  let n = t.getElementById("headlessui-portal-root");
  if (n)
    return n;
  let o = t.createElement("div");
  return o.setAttribute("id", "headlessui-portal-root"), t.body.appendChild(o);
}
let Un = C({ name: "Portal", props: { as: { type: [Object, String], default: "div" } }, setup(e, { slots: t, attrs: n }) {
  let o = k(null), a = S(() => Ve(o)), l = yo(), r = $e(Hn, null), s = k(l === !0 || r == null ? wo(o.value) : r.resolveTarget());
  return he(() => {
    l || r != null && (s.value = r.resolveTarget());
  }), Be(() => {
    var i, u;
    let c = (i = a.value) == null ? void 0 : i.getElementById("headlessui-portal-root");
    !c || s.value === c && s.value.children.length <= 0 && ((u = s.value.parentElement) == null || u.removeChild(s.value));
  }), () => {
    if (s.value === null)
      return null;
    let i = { ref: o, "data-headlessui-portal": "" };
    return N(ya, { to: s.value }, Y({ ourProps: i, theirProps: e, slot: {}, attrs: n, slots: t, name: "Portal" }));
  };
} }), Hn = Symbol("PortalGroupContext"), _o = C({ name: "PortalGroup", props: { as: { type: [Object, String], default: "template" }, target: { type: Object, default: null } }, setup(e, { attrs: t, slots: n }) {
  let o = Ft({ resolveTarget() {
    return e.target;
  } });
  return Ee(Hn, o), () => {
    let { target: a, ...l } = e;
    return Y({ theirProps: l, ourProps: {}, slot: {}, attrs: t, slots: n, name: "PortalGroup" });
  };
} }), Wn = Symbol("StackContext");
var Rt = ((e) => (e[e.Add = 0] = "Add", e[e.Remove = 1] = "Remove", e))(Rt || {});
function $o() {
  return $e(Wn, () => {
  });
}
function So({ type: e, element: t, onUpdate: n }) {
  let o = $o();
  function a(...l) {
    n == null || n(...l), o(...l);
  }
  K(() => {
    a(0, e, t), Be(() => {
      a(1, e, t);
    });
  }), Ee(Wn, a);
}
let qn = Symbol("DescriptionContext");
function ko() {
  let e = $e(qn, null);
  if (e === null)
    throw new Error("Missing parent");
  return e;
}
function xo({ slot: e = k({}), name: t = "Description", props: n = {} } = {}) {
  let o = k([]);
  function a(l) {
    return o.value.push(l), () => {
      let r = o.value.indexOf(l);
      r !== -1 && o.value.splice(r, 1);
    };
  }
  return Ee(qn, { register: a, slot: e, name: t, props: n }), S(() => o.value.length > 0 ? o.value.join(" ") : void 0);
}
let Io = C({ name: "Description", props: { as: { type: [Object, String], default: "p" } }, setup(e, { attrs: t, slots: n }) {
  let o = ko(), a = `headlessui-description-${Se()}`;
  return K(() => Be(o.register(a))), () => {
    let { name: l = "Description", slot: r = k({}), props: s = {} } = o, i = e, u = { ...Object.entries(s).reduce((c, [d, v]) => Object.assign(c, { [d]: B(v) }), {}), id: a };
    return Y({ ourProps: u, theirProps: i, slot: r.value, attrs: t, slots: n, name: l });
  };
} });
var Co = ((e) => (e[e.Open = 0] = "Open", e[e.Closed = 1] = "Closed", e))(Co || {});
let Lt = Symbol("DialogContext");
function it(e) {
  let t = $e(Lt, null);
  if (t === null) {
    let n = new Error(`<${e} /> is missing a parent <Dialog /> component.`);
    throw Error.captureStackTrace && Error.captureStackTrace(n, it), n;
  }
  return t;
}
let mt = "DC8F892D-2EBD-447C-A4C8-A03058436FF4", Po = C({ name: "Dialog", inheritAttrs: !1, props: { as: { type: [Object, String], default: "div" }, static: { type: Boolean, default: !1 }, unmount: { type: Boolean, default: !0 }, open: { type: [Boolean, String], default: mt }, initialFocus: { type: Object, default: null } }, emits: { close: (e) => !0 }, setup(e, { emit: t, attrs: n, slots: o, expose: a }) {
  var l;
  let r = k(!1);
  K(() => {
    r.value = !0;
  });
  let s = k(0), i = Bn(), u = S(() => e.open === mt && i !== null ? Me(i.value, { [qe.Open]: !0, [qe.Closed]: !1 }) : e.open), c = k(/* @__PURE__ */ new Set()), d = k(null), v = k(null), p = S(() => Ve(d));
  if (a({ el: d, $el: d }), !(e.open !== mt || i !== null))
    throw new Error("You forgot to provide an `open` prop to the `Dialog`.");
  if (typeof u.value != "boolean")
    throw new Error(`You provided an \`open\` prop to the \`Dialog\`, but the value is not a boolean. Received: ${u.value === mt ? void 0 : e.open}`);
  let f = S(() => r.value && u.value ? 0 : 1), y = S(() => f.value === 0), g = S(() => s.value > 1), _ = $e(Lt, null) !== null, M = S(() => g.value ? "parent" : "leaf");
  bo(d, S(() => g.value ? y.value : !1)), So({ type: "Dialog", element: d, onUpdate: (A, T, w) => {
    if (T === "Dialog")
      return Me(A, { [Rt.Add]() {
        c.value.add(w), s.value += 1;
      }, [Rt.Remove]() {
        c.value.delete(w), s.value -= 1;
      } });
  } });
  let O = xo({ name: "DialogDescription", slot: S(() => ({ open: u.value })) }), $ = `headlessui-dialog-${Se()}`, E = k(null), z = { titleId: E, panelRef: k(null), dialogState: f, setTitleId(A) {
    E.value !== A && (E.value = A);
  }, close() {
    t("close", !1);
  } };
  return Ee(Lt, z), Nn(() => {
    var A, T, w;
    return [...Array.from((T = (A = p.value) == null ? void 0 : A.querySelectorAll("body > *, [data-headlessui-portal]")) != null ? T : []).filter((D) => !(!(D instanceof HTMLElement) || D.contains(V(v)) || z.panelRef.value && D.contains(z.panelRef.value))), (w = z.panelRef.value) != null ? w : d.value];
  }, (A, T) => {
    z.close(), ye(() => T == null ? void 0 : T.focus());
  }, S(() => f.value === 0 && !g.value)), jn((l = p.value) == null ? void 0 : l.defaultView, "keydown", (A) => {
    A.defaultPrevented || A.key === F.Escape && f.value === 0 && (g.value || (A.preventDefault(), A.stopPropagation(), z.close()));
  }), he((A) => {
    var T;
    if (f.value !== 0 || _)
      return;
    let w = p.value;
    if (!w)
      return;
    let D = w == null ? void 0 : w.documentElement, U = (T = w.defaultView) != null ? T : window, ne = D.style.overflow, H = D.style.paddingRight, ae = U.innerWidth - D.clientWidth;
    if (D.style.overflow = "hidden", ae > 0) {
      let re = D.clientWidth - D.offsetWidth, W = ae - re;
      D.style.paddingRight = `${W}px`;
    }
    A(() => {
      D.style.overflow = ne, D.style.paddingRight = H;
    });
  }), he((A) => {
    if (f.value !== 0)
      return;
    let T = V(d);
    if (!T)
      return;
    let w = new IntersectionObserver((D) => {
      for (let U of D)
        U.boundingClientRect.x === 0 && U.boundingClientRect.y === 0 && U.boundingClientRect.width === 0 && U.boundingClientRect.height === 0 && z.close();
    });
    w.observe(T), A(() => w.disconnect());
  }), () => {
    let A = { ...n, ref: d, id: $, role: "dialog", "aria-modal": f.value === 0 ? !0 : void 0, "aria-labelledby": E.value, "aria-describedby": O.value }, { open: T, initialFocus: w, ...D } = e, U = { open: f.value === 0 };
    return N(Tt, { force: !0 }, () => [N(Un, () => N(_o, { target: d.value }, () => N(Tt, { force: !1 }, () => N(Ze, { initialFocus: w, containers: c, features: y.value ? Me(M.value, { parent: Ze.features.RestoreFocus, leaf: Ze.features.All & ~Ze.features.FocusLock }) : Ze.features.None }, () => Y({ ourProps: A, theirProps: D, slot: U, attrs: n, slots: o, visible: f.value === 0, features: je.RenderStrategy | je.Static, name: "Dialog" }))))), N($t, { features: et.Hidden, ref: v })]);
  };
} });
C({ name: "DialogOverlay", props: { as: { type: [Object, String], default: "div" } }, setup(e, { attrs: t, slots: n }) {
  let o = it("DialogOverlay"), a = `headlessui-dialog-overlay-${Se()}`;
  function l(r) {
    r.target === r.currentTarget && (r.preventDefault(), r.stopPropagation(), o.close());
  }
  return () => Y({ ourProps: { id: a, "aria-hidden": !0, onClick: l }, theirProps: e, slot: { open: o.dialogState.value === 0 }, attrs: t, slots: n, name: "DialogOverlay" });
} });
C({ name: "DialogBackdrop", props: { as: { type: [Object, String], default: "div" } }, inheritAttrs: !1, setup(e, { attrs: t, slots: n, expose: o }) {
  let a = it("DialogBackdrop"), l = `headlessui-dialog-backdrop-${Se()}`, r = k(null);
  return o({ el: r, $el: r }), K(() => {
    if (a.panelRef.value === null)
      throw new Error("A <DialogBackdrop /> component is being used, but a <DialogPanel /> component is missing.");
  }), () => {
    let s = e, i = { id: l, ref: r, "aria-hidden": !0 };
    return N(Tt, { force: !0 }, () => N(Un, () => Y({ ourProps: i, theirProps: { ...t, ...s }, slot: { open: a.dialogState.value === 0 }, attrs: t, slots: n, name: "DialogBackdrop" })));
  };
} });
C({ name: "DialogPanel", props: { as: { type: [Object, String], default: "div" } }, setup(e, { attrs: t, slots: n, expose: o }) {
  let a = it("DialogPanel"), l = `headlessui-dialog-panel-${Se()}`;
  o({ el: a.panelRef, $el: a.panelRef });
  function r(s) {
    s.stopPropagation();
  }
  return () => {
    let s = { id: l, ref: a.panelRef, onClick: r };
    return Y({ ourProps: s, theirProps: e, slot: { open: a.dialogState.value === 0 }, attrs: t, slots: n, name: "DialogPanel" });
  };
} });
let Oo = C({ name: "DialogTitle", props: { as: { type: [Object, String], default: "h2" } }, setup(e, { attrs: t, slots: n }) {
  let o = it("DialogTitle"), a = `headlessui-dialog-title-${Se()}`;
  return K(() => {
    o.setTitleId(a), Be(() => o.setTitleId(null));
  }), () => Y({ ourProps: { id: a }, theirProps: e, slot: { open: o.dialogState.value === 0 }, attrs: t, slots: n, name: "DialogTitle" });
} }), Ao = Io;
var Mo = ((e) => (e[e.Open = 0] = "Open", e[e.Closed = 1] = "Closed", e))(Mo || {}), Do = ((e) => (e[e.Pointer = 0] = "Pointer", e[e.Other = 1] = "Other", e))(Do || {});
function Eo(e) {
  requestAnimationFrame(() => requestAnimationFrame(e));
}
let Kn = Symbol("MenuContext");
function xt(e) {
  let t = $e(Kn, null);
  if (t === null) {
    let n = new Error(`<${e} /> is missing a parent <Menu /> component.`);
    throw Error.captureStackTrace && Error.captureStackTrace(n, xt), n;
  }
  return t;
}
let Yn = C({ name: "Menu", props: { as: { type: [Object, String], default: "template" } }, setup(e, { slots: t, attrs: n }) {
  let o = k(1), a = k(null), l = k(null), r = k([]), s = k(""), i = k(null), u = k(1);
  function c(v = (p) => p) {
    let p = i.value !== null ? r.value[i.value] : null, f = zn(v(r.value.slice()), (g) => V(g.dataRef.domRef)), y = p ? f.indexOf(p) : null;
    return y === -1 && (y = null), { items: f, activeItemIndex: y };
  }
  let d = { menuState: o, buttonRef: a, itemsRef: l, items: r, searchQuery: s, activeItemIndex: i, activationTrigger: u, closeMenu: () => {
    o.value = 1, i.value = null;
  }, openMenu: () => o.value = 0, goToItem(v, p, f) {
    let y = c(), g = lo(v === ce.Specific ? { focus: ce.Specific, id: p } : { focus: v }, { resolveItems: () => y.items, resolveActiveIndex: () => y.activeItemIndex, resolveId: (_) => _.id, resolveDisabled: (_) => _.dataRef.disabled });
    s.value = "", i.value = g, u.value = f != null ? f : 1, r.value = y.items;
  }, search(v) {
    let p = s.value !== "" ? 0 : 1;
    s.value += v.toLowerCase();
    let f = (i.value !== null ? r.value.slice(i.value + p).concat(r.value.slice(0, i.value + p)) : r.value).find((g) => g.dataRef.textValue.startsWith(s.value) && !g.dataRef.disabled), y = f ? r.value.indexOf(f) : -1;
    y === -1 || y === i.value || (i.value = y, u.value = 1);
  }, clearSearch() {
    s.value = "";
  }, registerItem(v, p) {
    let f = c((y) => [...y, { id: v, dataRef: p }]);
    r.value = f.items, i.value = f.activeItemIndex, u.value = 1;
  }, unregisterItem(v) {
    let p = c((f) => {
      let y = f.findIndex((g) => g.id === v);
      return y !== -1 && f.splice(y, 1), f;
    });
    r.value = p.items, i.value = p.activeItemIndex, u.value = 1;
  } };
  return Nn([a, l], (v, p) => {
    var f;
    d.closeMenu(), Ln(p, Ht.Loose) || (v.preventDefault(), (f = V(a)) == null || f.focus());
  }, S(() => o.value === 0)), Ee(Kn, d), ro(S(() => Me(o.value, { [0]: qe.Open, [1]: qe.Closed }))), () => {
    let v = { open: o.value === 0 };
    return Y({ ourProps: {}, theirProps: e, slot: v, slots: t, attrs: n, name: "Menu" });
  };
} }), Gn = C({ name: "MenuButton", props: { disabled: { type: Boolean, default: !1 }, as: { type: [Object, String], default: "button" } }, setup(e, { attrs: t, slots: n, expose: o }) {
  let a = xt("MenuButton"), l = `headlessui-menu-button-${Se()}`;
  o({ el: a.buttonRef, $el: a.buttonRef });
  function r(c) {
    switch (c.key) {
      case F.Space:
      case F.Enter:
      case F.ArrowDown:
        c.preventDefault(), c.stopPropagation(), a.openMenu(), ye(() => {
          var d;
          (d = V(a.itemsRef)) == null || d.focus({ preventScroll: !0 }), a.goToItem(ce.First);
        });
        break;
      case F.ArrowUp:
        c.preventDefault(), c.stopPropagation(), a.openMenu(), ye(() => {
          var d;
          (d = V(a.itemsRef)) == null || d.focus({ preventScroll: !0 }), a.goToItem(ce.Last);
        });
        break;
    }
  }
  function s(c) {
    switch (c.key) {
      case F.Space:
        c.preventDefault();
        break;
    }
  }
  function i(c) {
    e.disabled || (a.menuState.value === 0 ? (a.closeMenu(), ye(() => {
      var d;
      return (d = V(a.buttonRef)) == null ? void 0 : d.focus({ preventScroll: !0 });
    })) : (c.preventDefault(), a.openMenu(), Eo(() => {
      var d;
      return (d = V(a.itemsRef)) == null ? void 0 : d.focus({ preventScroll: !0 });
    })));
  }
  let u = Tn(S(() => ({ as: e.as, type: t.type })), a.buttonRef);
  return () => {
    var c;
    let d = { open: a.menuState.value === 0 }, v = { ref: a.buttonRef, id: l, type: u.value, "aria-haspopup": !0, "aria-controls": (c = V(a.itemsRef)) == null ? void 0 : c.id, "aria-expanded": e.disabled ? void 0 : a.menuState.value === 0, onKeydown: r, onKeyup: s, onClick: i };
    return Y({ ourProps: v, theirProps: e, slot: d, attrs: t, slots: n, name: "MenuButton" });
  };
} }), Zn = C({ name: "MenuItems", props: { as: { type: [Object, String], default: "div" }, static: { type: Boolean, default: !1 }, unmount: { type: Boolean, default: !0 } }, setup(e, { attrs: t, slots: n, expose: o }) {
  let a = xt("MenuItems"), l = `headlessui-menu-items-${Se()}`, r = k(null);
  o({ el: a.itemsRef, $el: a.itemsRef }), so({ container: S(() => V(a.itemsRef)), enabled: S(() => a.menuState.value === 0), accept(d) {
    return d.getAttribute("role") === "menuitem" ? NodeFilter.FILTER_REJECT : d.hasAttribute("role") ? NodeFilter.FILTER_SKIP : NodeFilter.FILTER_ACCEPT;
  }, walk(d) {
    d.setAttribute("role", "none");
  } });
  function s(d) {
    var v;
    switch (r.value && clearTimeout(r.value), d.key) {
      case F.Space:
        if (a.searchQuery.value !== "")
          return d.preventDefault(), d.stopPropagation(), a.search(d.key);
      case F.Enter:
        if (d.preventDefault(), d.stopPropagation(), a.activeItemIndex.value !== null) {
          let p = a.items.value[a.activeItemIndex.value];
          (v = V(p.dataRef.domRef)) == null || v.click();
        }
        a.closeMenu(), ye(() => {
          var p;
          return (p = V(a.buttonRef)) == null ? void 0 : p.focus({ preventScroll: !0 });
        });
        break;
      case F.ArrowDown:
        return d.preventDefault(), d.stopPropagation(), a.goToItem(ce.Next);
      case F.ArrowUp:
        return d.preventDefault(), d.stopPropagation(), a.goToItem(ce.Previous);
      case F.Home:
      case F.PageUp:
        return d.preventDefault(), d.stopPropagation(), a.goToItem(ce.First);
      case F.End:
      case F.PageDown:
        return d.preventDefault(), d.stopPropagation(), a.goToItem(ce.Last);
      case F.Escape:
        d.preventDefault(), d.stopPropagation(), a.closeMenu(), ye(() => {
          var p;
          return (p = V(a.buttonRef)) == null ? void 0 : p.focus({ preventScroll: !0 });
        });
        break;
      case F.Tab:
        d.preventDefault(), d.stopPropagation();
        break;
      default:
        d.key.length === 1 && (a.search(d.key), r.value = setTimeout(() => a.clearSearch(), 350));
        break;
    }
  }
  function i(d) {
    switch (d.key) {
      case F.Space:
        d.preventDefault();
        break;
    }
  }
  let u = Bn(), c = S(() => u !== null ? u.value === qe.Open : a.menuState.value === 0);
  return () => {
    var d, v;
    let p = { open: a.menuState.value === 0 }, f = { "aria-activedescendant": a.activeItemIndex.value === null || (d = a.items.value[a.activeItemIndex.value]) == null ? void 0 : d.id, "aria-labelledby": (v = V(a.buttonRef)) == null ? void 0 : v.id, id: l, onKeydown: s, onKeyup: i, role: "menu", tabIndex: 0, ref: a.itemsRef };
    return Y({ ourProps: f, theirProps: e, slot: p, attrs: t, slots: n, features: je.RenderStrategy | je.Static, visible: c.value, name: "MenuItems" });
  };
} }), Bo = C({ name: "MenuItem", props: { as: { type: [Object, String], default: "template" }, disabled: { type: Boolean, default: !1 } }, setup(e, { slots: t, attrs: n, expose: o }) {
  let a = xt("MenuItem"), l = `headlessui-menu-item-${Se()}`, r = k(null);
  o({ el: r, $el: r });
  let s = S(() => a.activeItemIndex.value !== null ? a.items.value[a.activeItemIndex.value].id === l : !1), i = S(() => ({ disabled: e.disabled, textValue: "", domRef: r }));
  K(() => {
    var p, f;
    let y = (f = (p = V(r)) == null ? void 0 : p.textContent) == null ? void 0 : f.toLowerCase().trim();
    y !== void 0 && (i.value.textValue = y);
  }), K(() => a.registerItem(l, i)), Be(() => a.unregisterItem(l)), he(() => {
    a.menuState.value === 0 && (!s.value || a.activationTrigger.value !== 0 && ye(() => {
      var p, f;
      return (f = (p = V(r)) == null ? void 0 : p.scrollIntoView) == null ? void 0 : f.call(p, { block: "nearest" });
    }));
  });
  function u(p) {
    if (e.disabled)
      return p.preventDefault();
    a.closeMenu(), ye(() => {
      var f;
      return (f = V(a.buttonRef)) == null ? void 0 : f.focus({ preventScroll: !0 });
    });
  }
  function c() {
    if (e.disabled)
      return a.goToItem(ce.Nothing);
    a.goToItem(ce.Specific, l);
  }
  function d() {
    e.disabled || s.value || a.goToItem(ce.Specific, l, 0);
  }
  function v() {
    e.disabled || !s.value || a.goToItem(ce.Nothing);
  }
  return () => {
    let { disabled: p } = e, f = { active: s.value, disabled: p };
    return Y({ ourProps: { id: l, ref: r, role: "menuitem", tabIndex: p === !0 ? void 0 : -1, "aria-disabled": p === !0 ? !0 : void 0, onClick: u, onFocus: c, onPointermove: d, onMousemove: d, onPointerleave: v, onMouseleave: v }, theirProps: e, slot: f, attrs: n, slots: t, name: "MenuItem" });
  };
} }), To = C({ props: { onFocus: { type: Function, required: !0 } }, setup(e) {
  let t = k(!0);
  return () => t.value ? N($t, { as: "button", type: "button", features: et.Focusable, onFocus(n) {
    n.preventDefault();
    let o, a = 50;
    function l() {
      var r;
      if (a-- <= 0) {
        o && cancelAnimationFrame(o);
        return;
      }
      if ((r = e.onFocus) != null && r.call(e)) {
        t.value = !1, cancelAnimationFrame(o);
        return;
      }
      o = requestAnimationFrame(l);
    }
    o = requestAnimationFrame(l);
  } }) : null;
} }), Xn = Symbol("TabsContext");
function ut(e) {
  let t = $e(Xn, null);
  if (t === null) {
    let n = new Error(`<${e} /> is missing a parent <TabGroup /> component.`);
    throw Error.captureStackTrace && Error.captureStackTrace(n, ut), n;
  }
  return t;
}
let Ro = C({ name: "TabGroup", emits: { change: (e) => !0 }, props: { as: { type: [Object, String], default: "template" }, selectedIndex: { type: [Number], default: null }, defaultIndex: { type: [Number], default: 0 }, vertical: { type: [Boolean], default: !1 }, manual: { type: [Boolean], default: !1 } }, inheritAttrs: !1, setup(e, { slots: t, attrs: n, emit: o }) {
  let a = k(null), l = k([]), r = k([]), s = { selectedIndex: a, orientation: S(() => e.vertical ? "vertical" : "horizontal"), activation: S(() => e.manual ? "manual" : "auto"), tabs: l, panels: r, setSelectedIndex(i) {
    a.value !== i && (a.value = i, o("change", i));
  }, registerTab(i) {
    l.value.includes(i) || l.value.push(i);
  }, unregisterTab(i) {
    let u = l.value.indexOf(i);
    u !== -1 && l.value.splice(u, 1);
  }, registerPanel(i) {
    r.value.includes(i) || r.value.push(i);
  }, unregisterPanel(i) {
    let u = r.value.indexOf(i);
    u !== -1 && r.value.splice(u, 1);
  } };
  return Ee(Xn, s), he(() => {
    var i;
    if (s.tabs.value.length <= 0 || e.selectedIndex === null && a.value !== null)
      return;
    let u = s.tabs.value.map((v) => V(v)).filter(Boolean), c = u.filter((v) => !v.hasAttribute("disabled")), d = (i = e.selectedIndex) != null ? i : e.defaultIndex;
    if (d < 0)
      a.value = u.indexOf(c[0]);
    else if (d > s.tabs.value.length)
      a.value = u.indexOf(c[c.length - 1]);
    else {
      let v = u.slice(0, d), p = [...u.slice(d), ...v].find((f) => c.includes(f));
      if (!p)
        return;
      a.value = u.indexOf(p);
    }
  }), () => {
    let i = { selectedIndex: a.value };
    return N(Z, [l.value.length <= 0 && N(To, { onFocus: () => {
      for (let u of l.value) {
        let c = V(u);
        if ((c == null ? void 0 : c.tabIndex) === 0)
          return c.focus(), !0;
      }
      return !1;
    } }), Y({ theirProps: { ...n, ...Dn(e, ["selectedIndex", "defaultIndex", "manual", "vertical", "onChange"]) }, ourProps: {}, slot: i, slots: t, attrs: n, name: "TabGroup" })]);
  };
} }), Lo = C({ name: "TabList", props: { as: { type: [Object, String], default: "div" } }, setup(e, { attrs: t, slots: n }) {
  let o = ut("TabList");
  return () => {
    let a = { selectedIndex: o.selectedIndex.value }, l = { role: "tablist", "aria-orientation": o.orientation.value };
    return Y({ ourProps: l, theirProps: e, slot: a, attrs: t, slots: n, name: "TabList" });
  };
} }), zo = C({ name: "Tab", props: { as: { type: [Object, String], default: "button" }, disabled: { type: [Boolean], default: !1 } }, setup(e, { attrs: t, slots: n, expose: o }) {
  let a = ut("Tab"), l = `headlessui-tabs-tab-${Se()}`, r = k(null);
  o({ el: r, $el: r }), K(() => a.registerTab(r)), Be(() => a.unregisterTab(r));
  let s = S(() => a.tabs.value.indexOf(r)), i = S(() => s.value === a.selectedIndex.value);
  function u(f) {
    let y = a.tabs.value.map((g) => V(g)).filter(Boolean);
    if (f.key === F.Space || f.key === F.Enter) {
      f.preventDefault(), f.stopPropagation(), a.setSelectedIndex(s.value);
      return;
    }
    switch (f.key) {
      case F.Home:
      case F.PageUp:
        return f.preventDefault(), f.stopPropagation(), Ae(y, le.First);
      case F.End:
      case F.PageDown:
        return f.preventDefault(), f.stopPropagation(), Ae(y, le.Last);
    }
    if (Me(a.orientation.value, { vertical() {
      if (f.key === F.ArrowUp)
        return Ae(y, le.Previous | le.WrapAround);
      if (f.key === F.ArrowDown)
        return Ae(y, le.Next | le.WrapAround);
    }, horizontal() {
      if (f.key === F.ArrowLeft)
        return Ae(y, le.Previous | le.WrapAround);
      if (f.key === F.ArrowRight)
        return Ae(y, le.Next | le.WrapAround);
    } }))
      return f.preventDefault();
  }
  function c() {
    var f;
    (f = V(r)) == null || f.focus();
  }
  function d() {
    var f;
    e.disabled || ((f = V(r)) == null || f.focus(), a.setSelectedIndex(s.value));
  }
  function v(f) {
    f.preventDefault();
  }
  let p = Tn(S(() => ({ as: e.as, type: t.type })), r);
  return () => {
    var f, y;
    let g = { selected: i.value }, _ = { ref: r, onKeydown: u, onFocus: a.activation.value === "manual" ? c : d, onMousedown: v, onClick: d, id: l, role: "tab", type: p.value, "aria-controls": (y = (f = a.panels.value[s.value]) == null ? void 0 : f.value) == null ? void 0 : y.id, "aria-selected": i.value, tabIndex: i.value ? 0 : -1, disabled: e.disabled ? !0 : void 0 };
    return Y({ ourProps: _, theirProps: e, slot: g, attrs: t, slots: n, name: "Tab" });
  };
} }), No = C({ name: "TabPanels", props: { as: { type: [Object, String], default: "div" } }, setup(e, { slots: t, attrs: n }) {
  let o = ut("TabPanels");
  return () => {
    let a = { selectedIndex: o.selectedIndex.value };
    return Y({ theirProps: e, ourProps: {}, slot: a, attrs: n, slots: t, name: "TabPanels" });
  };
} }), jo = C({ name: "TabPanel", props: { as: { type: [Object, String], default: "div" }, static: { type: Boolean, default: !1 }, unmount: { type: Boolean, default: !0 } }, setup(e, { attrs: t, slots: n, expose: o }) {
  let a = ut("TabPanel"), l = `headlessui-tabs-panel-${Se()}`, r = k(null);
  o({ el: r, $el: r }), K(() => a.registerPanel(r)), Be(() => a.unregisterPanel(r));
  let s = S(() => a.panels.value.indexOf(r)), i = S(() => s.value === a.selectedIndex.value);
  return () => {
    var u, c;
    let d = { selected: i.value }, v = { ref: r, id: l, role: "tabpanel", "aria-labelledby": (c = (u = a.tabs.value[s.value]) == null ? void 0 : u.value) == null ? void 0 : c.id, tabIndex: i.value ? 0 : -1 };
    return Y({ ourProps: v, theirProps: e, slot: d, attrs: t, slots: n, features: je.Static | je.RenderStrategy, visible: i.value, name: "TabPanel" });
  };
} });
const Vo = /* @__PURE__ */ C({
  __name: "Dropdown",
  props: {
    variant: { default: "primary" },
    disabled: { type: Boolean, default: !1 }
  },
  setup(e) {
    return (t, n) => {
      const o = kt, a = st, l = ie;
      return m(), R(B(Yn), {
        as: "div",
        class: "dropdown--wrapper"
      }, {
        default: I(() => [
          x(B(Gn), {
            class: j(["dropdown--button", {
              "dropdown--button--primary": e.variant === "primary",
              "dropdown--button--secondary": e.variant === "secondary",
              "dropdown--button--danger": e.variant === "danger"
            }]),
            disabled: e.disabled
          }, {
            default: I(({ open: r }) => [
              P(t.$slots, "dropdownButton"),
              x(l, { class: "dropdown--icon" }, {
                default: I(() => [
                  r ? (m(), R(o, { key: 0 })) : (m(), R(a, { key: 1 }))
                ]),
                _: 2
              }, 1024)
            ]),
            _: 3
          }, 8, ["class", "disabled"]),
          x(B(Zn), {
            as: "div",
            class: j(["dropdown--items", {
              "dropdown--items--primary": e.variant === "primary",
              "dropdown--items--secondary": e.variant === "secondary",
              "dropdown--items--danger": e.variant === "danger"
            }])
          }, {
            default: I(() => [
              P(t.$slots, "dropdownItems")
            ]),
            _: 3
          }, 8, ["class"])
        ]),
        _: 3
      });
    };
  }
});
const Fo = /* @__PURE__ */ C({
  __name: "DropdownItem",
  props: {
    disabled: { type: Boolean, default: !1 }
  },
  setup(e) {
    return (t, n) => {
      const o = yt("svws-ui-icon"), a = lt;
      return m(), R(B(Bo), { disabled: e.disabled }, {
        default: I(({ active: l }) => [
          x(a, {
            class: j(["dropdown--item", {
              "dropdown--item--active": l === !0
            }])
          }, {
            default: I(() => [
              x(o, { class: "dropdown--item--icon" }, {
                default: I(() => [
                  P(t.$slots, "icon")
                ]),
                _: 3
              }),
              P(t.$slots, "default")
            ]),
            _: 2
          }, 1032, ["class"])
        ]),
        _: 3
      }, 8, ["disabled"]);
    };
  }
});
const Uo = /* @__PURE__ */ C({
  __name: "DropdownWithAction",
  props: {
    variant: { default: "primary" },
    dropdownDisabled: { type: Boolean, default: !1 }
  },
  setup(e) {
    return (t, n) => {
      const o = kt, a = st, l = ie;
      return m(), R(B(Yn), {
        as: "div",
        class: "dropdown-with-action--wrapper"
      }, {
        default: I(() => [
          P(t.$slots, "actionButton"),
          x(B(Gn), {
            class: j(["dropdown-with-action--button", {
              "dropdown-with-action--button--primary": e.variant === "primary",
              "dropdown-with-action--button--secondary": e.variant === "secondary",
              "dropdown-with-action--button--danger": e.variant === "danger"
            }]),
            disabled: e.dropdownDisabled
          }, {
            default: I(({ open: r }) => [
              x(l, { class: "dropdown-with-action--icon" }, {
                default: I(() => [
                  r ? (m(), R(o, { key: 0 })) : (m(), R(a, { key: 1 }))
                ]),
                _: 2
              }, 1024)
            ]),
            _: 1
          }, 8, ["class", "disabled"]),
          x(B(Zn), {
            as: "div",
            class: j(["dropdown--items", {
              "dropdown--items--primary": e.variant === "primary",
              "dropdown--items--secondary": e.variant === "secondary",
              "dropdown--items--danger": e.variant === "danger"
            }])
          }, {
            default: I(() => [
              P(t.$slots, "dropdownItems")
            ]),
            _: 3
          }, 8, ["class"])
        ]),
        _: 3
      });
    };
  }
});
const Wt = /* @__PURE__ */ C({
  __name: "Badge",
  props: {
    variant: { default: "light" },
    size: { default: "normal" }
  },
  setup(e) {
    return (t, n) => (m(), b("span", {
      class: j(["badge", {
        "badge--primary": e.variant === "primary",
        "badge--success": e.variant === "success",
        "badge--error": e.variant === "error",
        "badge--highlight": e.variant === "highlight",
        "badge--light": e.variant === "light",
        "badge--big": e.size === "big",
        "badge--medium": e.size === "medium",
        "badge--normal": e.size === "normal",
        "badge--small": e.size === "small",
        "badge--tiny": e.size === "tiny"
      }])
    }, [
      P(t.$slots, "default")
    ], 2));
  }
});
const Ho = {
  preserveAspectRatio: "xMidYMid meet",
  viewBox: "0 0 24 24",
  width: "1.2em",
  height: "1.2em"
}, Wo = /* @__PURE__ */ h("path", {
  fill: "currentColor",
  d: "m12 10.586l4.95-4.95l1.414 1.414l-4.95 4.95l4.95 4.95l-1.414 1.414l-4.95-4.95l-4.95 4.95l-1.414-1.414l4.95-4.95l-4.95-4.95L7.05 5.636z"
}, null, -1), qo = [
  Wo
];
function Ko(e, t) {
  return m(), b("svg", Ho, qo);
}
const Qn = { name: "ri-close-line", render: Ko }, Yo = {
  preserveAspectRatio: "xMidYMid meet",
  viewBox: "0 0 24 24",
  width: "1.2em",
  height: "1.2em"
}, Go = /* @__PURE__ */ h("path", {
  fill: "currentColor",
  d: "M17 3h4a1 1 0 0 1 1 1v16a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h4V1h2v2h6V1h2v2zm-2 2H9v2H7V5H4v4h16V5h-3v2h-2V5zm5 6H4v8h16v-8z"
}, null, -1), Zo = [
  Go
];
function Xo(e, t) {
  return m(), b("svg", Yo, Zo);
}
const Qo = { name: "ri-calendar-line", render: Xo }, Jo = ["type", "value", "disabled", "required", "readonly"], Jn = /* @__PURE__ */ C({
  __name: "TextInput",
  props: {
    type: { default: "text" },
    modelValue: { default: "" },
    placeholder: { default: "" },
    statistics: { type: Boolean, default: !1 },
    valid: { type: Boolean, default: !0 },
    disabled: { type: Boolean, default: !1 },
    required: { type: Boolean, default: !1 },
    readonly: { type: Boolean, default: !1 },
    headless: { type: Boolean, default: !1 }
  },
  emits: ["update:modelValue", "focus", "blur", "click", "mousedown", "keydown"],
  setup(e, { expose: t, emit: n }) {
    const o = Pn(), a = k(null), l = k(!1), r = S(() => e.type !== "email" || !e.modelValue ? !0 : /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))[^@]?$/.test(
      e.modelValue
    ) || /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(
      e.modelValue
    )), s = S(() => !!o.default);
    function i(f) {
      n("update:modelValue", f.target.value);
    }
    function u(f) {
      l.value = !0, n("focus", f);
    }
    function c(f) {
      l.value = !1, n("blur", f);
    }
    function d(f) {
      n("click", f);
    }
    function v(f) {
      n("mousedown", f);
    }
    function p(f) {
      n("keydown", f);
    }
    return t({
      input: a
    }), (f, y) => {
      const g = rt, _ = ie, M = Qo;
      return m(), b("label", {
        class: j(["text-input-component", {
          "text-input-focus": l.value,
          "text-input-filled": !!e.modelValue || e.type === "number" && e.modelValue === "0",
          "text-input-invalid": !e.valid || !B(r),
          "text-input-disabled": e.disabled,
          "text-input-readonly": e.readonly,
          "text-input--icon": B(s),
          "text-input--statistics": e.statistics
        }])
      }, [
        h("input", {
          ref_key: "input",
          ref: a,
          class: j({
            "text-input--control": !e.headless,
            "text-input--headless": e.headless
          }),
          type: e.type,
          value: e.modelValue,
          disabled: e.disabled,
          required: e.required,
          readonly: e.readonly,
          onInput: i,
          onFocus: u,
          onBlur: c,
          onClick: d,
          onMousedown: v,
          onKeydown: p
        }, null, 42, Jo),
        e.placeholder && !e.headless ? (m(), b("span", {
          key: 0,
          class: j(["text-input--placeholder", {
            "text-input--placeholder--required": e.required
          }])
        }, [
          Ne(q(e.placeholder) + " ", 1),
          e.statistics ? (m(), R(g, {
            key: 0,
            class: "ml-2"
          })) : L("", !0)
        ], 2)) : L("", !0),
        e.type !== "date" && B(s) ? (m(), R(_, { key: 1 }, {
          default: I(() => [
            P(f.$slots, "default")
          ]),
          _: 3
        })) : e.type === "date" ? (m(), R(_, {
          key: 2,
          class: "text-input--calendar-icon"
        }, {
          default: I(() => [
            x(M)
          ]),
          _: 1
        })) : L("", !0)
      ], 2);
    };
  }
});
const el = (e) => (wa("data-v-6c895fc6"), e = e(), _a(), e), tl = ["onClick"], nl = ["onClick"], al = ["onClick"], ol = /* @__PURE__ */ el(() => /* @__PURE__ */ h("span", { class: "py-1" }, "Keine Auswahl", -1)), ll = ["onClick"], rl = { class: "input" }, sl = {
  key: 0,
  class: "text-input--placeholder"
}, il = { class: "multiselect--items-wrapper" }, ul = ["onClick", "onMouseenter"], cl = /* @__PURE__ */ C({
  __name: "MultiSelect",
  props: {
    placeholder: { type: String, default: "" },
    title: { type: String, default: "" },
    tags: { type: Boolean, default: !1 },
    autocomplete: { type: Boolean, default: !1 },
    disabled: { type: Boolean, default: !1 },
    statistics: { type: Boolean, default: !1 },
    items: { type: [Array, Object], default() {
      return [];
    } },
    itemText: { type: Function, default(e) {
      return e.text || "";
    } },
    itemSort: { type: Function, default: null },
    itemFilter: { type: Function, default: null },
    modelValue: { type: Object, default() {
      return null;
    } },
    headless: { type: Boolean, default: !1 }
  },
  emits: ["update:modelValue", "focus", "blur"],
  setup(e, { emit: t }) {
    const n = k(null), o = k(Array.isArray(e.modelValue) ? e.modelValue[0] : e.modelValue), a = k(Array.isArray(e.modelValue) ? e.modelValue : [e.modelValue]), l = k(0), r = k(!1), s = k(!e.tags), i = k(""), u = k([]), c = S({
      get() {
        return e.modelValue ? e.itemText(o.value) : "";
      },
      set(w) {
        i.value = w.toString();
      }
    });
    J(() => e.modelValue, (w) => {
      o.value = Array.isArray(w) ? w[0] : w, a.value = Array.isArray(w) ? w : [w];
    });
    const d = S(() => e.autocomplete && f.value.length ? r.value && f.value.length && i.value.length > 1 : r.value);
    function v(w) {
      return Symbol.iterator in w;
    }
    const p = S(() => {
      if (!v(e.items))
        return [];
      const w = Array.isArray(e.items) ? e.items : [...e.items];
      return e.itemSort ? w.sort(e.itemSort) : w;
    }), f = S(() => e.autocomplete ? e.itemFilter ? e.itemFilter(p.value, i.value) : p.value.filter((w) => {
      e.itemText(w).startsWith(i.value);
    }) : p.value);
    function y() {
      !d.value || g(
        f.value[l.value - 1]
      );
    }
    function g(w) {
      var D;
      o.value = w, e.tags ? a.value.includes(w) ? a.value.splice(
        a.value.indexOf(w),
        1
      ) : a.value.push(w) : a.value = [w], (D = n.value) == null || D.input.blur(), c.value = e.itemText(w), t(
        "update:modelValue",
        e.tags ? a.value : o.value
      );
    }
    function _(w) {
      a.value.splice(w, 1);
    }
    function M(w) {
      r.value = !0, t("focus", w);
    }
    function O() {
      var w;
      e.autocomplete ? s.value ? (s.value = !1, (w = n.value) == null || w.input.blur(), r.value = !1) : (s.value = !0, ye(() => {
        var D;
        return (D = n.value) == null ? void 0 : D.input.focus();
      })) : r.value = !r.value;
    }
    function $() {
      var w;
      (w = n.value) == null || w.input.blur(), r.value = !1, c.value = "";
    }
    function E() {
      e.tags || (r.value = !1), c.value = "", Array.from(
        document.getElementsByClassName(
          "multiselect--item active"
        )
      ).forEach((w) => {
        w.classList.remove("active");
      }), l.value = 0;
    }
    function z() {
      const w = f.value.length;
      l.value < w ? l.value++ : l.value === w && (l.value = 1), T();
    }
    function A() {
      const w = f.value.length;
      l.value === 0 || l.value === 1 ? l.value = w : l.value > 1 && l.value--, T();
    }
    function T() {
      var w;
      (w = u.value[l.value]) == null || w.scrollIntoView({
        block: "center",
        inline: "nearest"
      });
    }
    return (w, D) => {
      const U = Qn, ne = ie, H = Wt, ae = kt, re = st;
      return m(), b("div", {
        class: j(["wrapper", { "z-50": B(d) }])
      }, [
        e.tags ? (m(), b("div", {
          key: 0,
          class: j(["text-input--control tags", {
            "multiselect--input--open": B(d),
            "text-input--headless": e.headless
          }]),
          tabindex: "1",
          onBlur: $,
          onClick: de(O, ["self"])
        }, [
          h("div", {
            class: "tag-list-wrapper",
            onClick: de(O, ["self"])
          }, [
            h("div", {
              class: "tag-list",
              onClick: de(O, ["self"])
            }, [
              a.value.length ? (m(!0), b(Z, { key: 1 }, ue(a.value, (W, X) => (m(), b("div", {
                key: X,
                class: "tag"
              }, [
                x(H, {
                  size: "small",
                  variant: "light",
                  class: "inline-flex items-center"
                }, {
                  default: I(() => [
                    h("span", null, q(e.itemText(W)), 1),
                    h("span", {
                      class: "tag-remove ml-1",
                      onClick: (G) => _(X)
                    }, [
                      x(ne, { class: "mt-1" }, {
                        default: I(() => [
                          x(U)
                        ]),
                        _: 1
                      })
                    ], 8, ll)
                  ]),
                  _: 2
                }, 1024)
              ]))), 128)) : P(w.$slots, "no-content", { key: 0 }, () => [
                ol
              ], !0)
            ], 8, al),
            x(ne, {
              class: "dropdown--icon dropdown-icon",
              onClick: de(O, ["self"])
            }, {
              default: I(() => [
                r.value ? (m(), R(ae, {
                  key: 0,
                  class: "pointer-events-none"
                })) : (m(), R(re, {
                  key: 1,
                  class: "pointer-events-none"
                }))
              ]),
              _: 1
            }, 8, ["onClick"])
          ], 8, nl)
        ], 42, tl)) : L("", !0),
        te(h("div", rl, [
          h("label", {
            class: j(["text-input-component", {
              "text-input-filled": !!e.modelValue,
              "text-input-focus": r.value,
              "text-input-disabled": e.disabled
            }])
          }, [
            x(Jn, {
              ref_key: "inputEl",
              ref: n,
              class: j({
                "multiselect--input--open": s.value
              }),
              "model-value": B(c),
              readonly: !e.autocomplete,
              placeholder: e.title,
              statistics: e.statistics,
              headless: e.headless,
              disabled: e.disabled,
              "onUpdate:modelValue": D[0] || (D[0] = (W) => c.value = String(W)),
              onFocus: M,
              onBlur: E,
              onKeydown: [
                be(de(z, ["prevent"]), ["down"]),
                be(de(A, ["prevent"]), ["up"]),
                be(de(y, ["prevent"]), ["enter", "tab"]),
                be(de($, ["prevent"]), ["esc"])
              ]
            }, null, 8, ["class", "model-value", "readonly", "placeholder", "statistics", "headless", "disabled", "onKeydown"]),
            e.placeholder ? (m(), b("span", sl, q(e.placeholder), 1)) : L("", !0),
            x(ne, { class: "dropdown--icon" }, {
              default: I(() => [
                r.value ? (m(), R(ae, { key: 0 })) : (m(), R(re, { key: 1 }))
              ]),
              _: 1
            })
          ], 2)
        ], 512), [
          [pe, s.value]
        ]),
        te(h("ul", il, [
          (m(!0), b(Z, null, ue(B(f), (W, X) => (m(), b("li", {
            key: X,
            ref_for: !0,
            ref_key: "itemRefs",
            ref: u,
            class: j(["multiselect--item", {
              active: l.value === X + 1,
              selected: a.value.includes(W)
            }]),
            onMousedown: D[1] || (D[1] = de(() => {
            }, ["prevent"])),
            onClick: (G) => g(W),
            onMouseenter: (G) => l.value = X + 1
          }, q(e.itemText(W)), 43, ul))), 128))
        ], 512), [
          [pe, B(d)]
        ])
      ], 2);
    };
  }
});
const ea = /* @__PURE__ */ Pe(cl, [["__scopeId", "data-v-6c895fc6"]]), dl = { class: "progress-bar--label" }, pl = { class: "progress-bar--percentage" }, fl = { class: "progress-bar--bar" }, vl = /* @__PURE__ */ C({
  __name: "ProgressBar",
  props: {
    size: { default: "default" },
    progress: { default: 0 }
  },
  setup(e) {
    return (t, n) => (m(), b("div", {
      class: j(["progress-bar", {
        "progress-bar--default": e.size === "default",
        "progress-bar--small": e.size === "small"
      }])
    }, [
      h("div", dl, [
        P(t.$slots, "default")
      ]),
      h("div", pl, q(`${Number(e.progress.toFixed(2))} %`), 1),
      h("div", fl, [
        h("div", {
          class: "progress-bar--completed",
          style: On({ width: `${e.progress}%` })
        }, null, 4)
      ])
    ], 2));
  }
});
const ml = {}, hl = { class: "radio" };
function gl(e, t) {
  return m(), b("div", hl, [
    P(e.$slots, "default")
  ]);
}
const bl = /* @__PURE__ */ Pe(ml, [["render", gl]]), yl = ["name", "value", "disabled"], wl = { class: "radio--label" }, _l = /* @__PURE__ */ C({
  __name: "RadioOption",
  props: {
    name: { default: "" },
    label: { default: "" },
    value: { default: "" },
    disabled: { type: Boolean, default: !1 },
    statistics: { type: Boolean, default: !1 },
    modelValue: { default: "" }
  },
  emits: ["update:modelValue"],
  setup(e, { emit: t }) {
    function n(a) {
      e.disabled || t("update:modelValue", a.target.value);
    }
    const o = S({
      get() {
        return e.modelValue;
      },
      set(a) {
        t("update:modelValue", a);
      }
    });
    return (a, l) => {
      const r = rt;
      return m(), b("label", {
        class: j(["radio--label", {
          "radio--label--disabled": e.disabled,
          "radio--statistics": e.statistics
        }])
      }, [
        te(h("input", {
          "onUpdate:modelValue": l[0] || (l[0] = (s) => ot(o) ? o.value = s : null),
          type: "radio",
          name: e.name,
          value: e.value,
          disabled: e.disabled,
          class: "radio--indicator",
          onInput: n
        }, null, 40, yl), [
          [$a, B(o)]
        ]),
        h("span", wl, [
          Ne(q(e.label) + " ", 1),
          e.statistics ? (m(), R(r, {
            key: 0,
            class: "ml-2"
          })) : L("", !0)
        ])
      ], 2);
    };
  }
});
const $l = ["disabled"], Sl = {
  key: 0,
  disabled: "",
  selected: ""
}, kl = ["value", "disabled"], xl = {
  key: 0,
  class: "select-input--placeholder"
}, Il = /* @__PURE__ */ C({
  __name: "SelectInput",
  props: {
    placeholder: { default: "" },
    options: { default: () => [] },
    valid: { type: Boolean, default: !0 },
    disabled: { type: Boolean, default: !1 },
    modelValue: { default: "" }
  },
  emits: ["update:modelValue", "focus", "blur", "click", "mousedown", "keydown"],
  setup(e, { emit: t }) {
    const n = S({
      get() {
        return e.modelValue;
      },
      set(u) {
        t("update:modelValue", u);
      }
    }), o = k(!1);
    K(() => {
      e.options.forEach((u) => {
        "selected" in u && (n.value = u.index);
      });
    });
    function a(u) {
      o.value = !0, t("focus", u);
    }
    function l(u) {
      o.value = !1, t("blur", u);
    }
    function r(u) {
      t("click", u);
    }
    function s(u) {
      t("mousedown", u);
    }
    function i(u) {
      t("keydown", u);
    }
    return (u, c) => {
      const d = kt, v = st, p = ie;
      return m(), b("label", {
        class: j(["select-input", {
          "select-input-filled": !!B(n),
          "select-input-focus": o.value,
          "select-input-invalid": !e.valid,
          "select-input-disabled": e.disabled
        }])
      }, [
        te(h("select", {
          "onUpdate:modelValue": c[0] || (c[0] = (f) => ot(n) ? n.value = f : null),
          class: "select-input--control",
          disabled: e.disabled,
          onFocus: a,
          onBlur: l,
          onClick: r,
          onMousedown: s,
          onOnkeydown: i
        }, [
          B(n) ? L("", !0) : (m(), b("option", Sl, q(e.placeholder), 1)),
          (m(!0), b(Z, null, ue(e.options, (f) => (m(), b("option", {
            key: f.index,
            value: f.index,
            disabled: f.disabled
          }, q(f.label), 9, kl))), 128))
        ], 40, $l), [
          [Sa, B(n)]
        ]),
        e.placeholder ? (m(), b("span", xl, q(e.placeholder), 1)) : L("", !0),
        x(p, { class: "dropdown--icon" }, {
          default: I(() => [
            o.value ? (m(), R(d, { key: 0 })) : (m(), R(v, { key: 1 }))
          ]),
          _: 1
        })
      ], 2);
    };
  }
});
const Cl = {
  preserveAspectRatio: "xMidYMid meet",
  viewBox: "0 0 24 24",
  width: "1.2em",
  height: "1.2em"
}, Pl = /* @__PURE__ */ h("path", {
  fill: "currentColor",
  d: "m16.172 11l-5.364-5.364l1.414-1.414L20 12l-7.778 7.778l-1.414-1.414L16.172 13H4v-2z"
}, null, -1), Ol = [
  Pl
];
function Al(e, t) {
  return m(), b("svg", Cl, Ol);
}
const Ml = { name: "ri-arrow-right-line", render: Al }, Dl = {
  preserveAspectRatio: "xMidYMid meet",
  viewBox: "0 0 24 24",
  width: "1.2em",
  height: "1.2em"
}, El = /* @__PURE__ */ h("path", {
  fill: "currentColor",
  d: "M7.828 11H20v2H7.828l5.364 5.364l-1.414 1.414L4 12l7.778-7.778l1.414 1.414z"
}, null, -1), Bl = [
  El
];
function Tl(e, t) {
  return m(), b("svg", Dl, Bl);
}
const Rl = { name: "ri-arrow-left-line", render: Tl }, Ll = {
  key: 0,
  class: "tab-bar--scroll-button-background tab-bar--scroll-button-background-left"
}, zl = {
  key: 1,
  class: "tab-bar--scroll-button-background tab-bar--scroll-button-background-right"
}, Nl = /* @__PURE__ */ C({
  __name: "TabBar",
  props: {
    modelValue: { default: 0 }
  },
  emits: ["update:modelValue"],
  setup(e, { emit: t }) {
    const n = k(), o = S({
      get() {
        return e.modelValue;
      },
      set(i) {
        t("update:modelValue", i);
      }
    }), a = k({
      scrolled: !1,
      scrolledMax: !1,
      scrollFactor: 4,
      maxScrollLeft: 0,
      tabs: []
    });
    K(() => {
      var i, u, c, d, v, p, f;
      a.value.maxScrollLeft = ((u = (i = n.value) == null ? void 0 : i.scrollWidth) != null ? u : 0) - ((d = (c = n.value) == null ? void 0 : c.clientWidth) != null ? d : 0), a.value.scrolledMax = ((p = (v = n.value) == null ? void 0 : v.scrollLeft) != null ? p : 0) >= a.value.maxScrollLeft, (f = n.value) == null || f.addEventListener("scroll", l), window.addEventListener("resize", l);
    }), Be(() => {
      var i;
      (i = n.value) == null || i.removeEventListener("scroll", l), window.removeEventListener("resize", l);
    }), ka(() => {
      l();
    });
    function l() {
      var i, u, c, d, v, p, f, y;
      a.value.scrolled = ((u = (i = n.value) == null ? void 0 : i.scrollLeft) != null ? u : 0) > 0, a.value.maxScrollLeft = ((d = (c = n.value) == null ? void 0 : c.scrollWidth) != null ? d : 0) - ((p = (v = n.value) == null ? void 0 : v.clientWidth) != null ? p : 0), a.value.scrolledMax = ((y = (f = n.value) == null ? void 0 : f.scrollLeft) != null ? y : 0) >= a.value.maxScrollLeft;
    }
    function r(i) {
      var c;
      const u = i == "left" ? -1 : 1;
      (c = n.value) == null || c.scrollBy({
        top: 0,
        left: u * n.value.scrollWidth / a.value.scrollFactor,
        behavior: "smooth"
      });
    }
    function s(i) {
      o.value = i;
    }
    return (i, u) => {
      const c = Rl, d = ie, v = Ml;
      return m(), R(B(Ro), {
        "selected-index": B(o),
        onChange: s
      }, {
        default: I(() => [
          x(B(Lo), {
            as: "div",
            class: "tab-bar--wrapper print:hidden"
          }, {
            default: I(() => [
              a.value.scrolled ? (m(), b("div", Ll, [
                h("button", {
                  class: "tab-bar--scroll-button",
                  onClick: u[0] || (u[0] = (p) => r("left"))
                }, [
                  x(d, null, {
                    default: I(() => [
                      x(c)
                    ]),
                    _: 1
                  })
                ])
              ])) : L("", !0),
              h("div", {
                ref_key: "contentEl",
                ref: n,
                class: "tab-bar"
              }, [
                P(i.$slots, "tabs")
              ], 512),
              a.value.scrolledMax ? L("", !0) : (m(), b("div", zl, [
                h("button", {
                  class: "tab-bar--scroll-button",
                  onClick: u[1] || (u[1] = (p) => r("right"))
                }, [
                  x(d, null, {
                    default: I(() => [
                      x(v)
                    ]),
                    _: 1
                  })
                ])
              ]))
            ]),
            _: 3
          }),
          x(B(No), { as: "template" }, {
            default: I(() => [
              P(i.$slots, "panels")
            ]),
            _: 3
          })
        ]),
        _: 3
      }, 8, ["selected-index"]);
    };
  }
});
const jl = /* @__PURE__ */ C({
  __name: "TabButton",
  props: {
    disabled: { type: Boolean, default: !1 }
  },
  setup(e) {
    return (t, n) => (m(), R(B(zo), {
      as: "template",
      disabled: e.disabled
    }, {
      default: I(({ selected: o }) => [
        h("button", {
          class: j(["tab", [o ? "tab--active" : ""]])
        }, [
          P(t.$slots, "default")
        ], 2)
      ]),
      _: 3
    }, 8, ["disabled"]));
  }
});
const Vl = /* @__PURE__ */ C({
  __name: "TabPanel",
  setup(e) {
    return (t, n) => (m(), R(B(jo), {
      as: "div",
      class: "tab-container"
    }, {
      default: I(() => [
        P(t.$slots, "default")
      ]),
      _: 3
    }));
  }
});
const Fl = ["value", "required", "disabled"], Ul = /* @__PURE__ */ C({
  __name: "TextareaInput",
  props: {
    modelValue: { default: "" },
    placeholder: { default: "" },
    valid: { type: Boolean, default: !0 },
    statistics: { type: Boolean, default: !1 },
    required: { type: Boolean, default: !1 },
    disabled: { type: Boolean, default: !1 },
    resizeable: { default: "both" }
  },
  emits: ["update:modelValue", "focus", "blur", "click", "mousedown", "keydown"],
  setup(e, { emit: t }) {
    const n = k(!1);
    function o(u) {
      t("update:modelValue", u.target.value);
    }
    function a(u) {
      n.value = !0, t("focus", u);
    }
    function l(u) {
      n.value = !1, t("blur", u);
    }
    function r(u) {
      t("click", u);
    }
    function s(u) {
      t("mousedown", u);
    }
    function i(u) {
      t("keydown", u);
    }
    return (u, c) => {
      const d = rt;
      return m(), b("label", {
        class: j(["textarea-input", {
          "textarea-input-focus": n.value,
          "textarea-input-filled": !!e.modelValue,
          "textarea-input-invalid": !e.valid,
          "textarea-input-disabled": e.disabled,
          "textarea-input--statistics": e.statistics,
          "textarea-input--resize-none": e.resizeable === "none",
          "textarea-input--resize-horizontal": e.resizeable === "horizontal",
          "textarea-input--resize-vertical": e.resizeable === "vertical",
          "textarea-input--resize-both": e.resizeable === "both"
        }])
      }, [
        h("textarea", {
          class: "textarea-input--control",
          value: e.modelValue,
          required: e.required,
          disabled: e.disabled,
          onInput: o,
          onFocus: a,
          onBlur: l,
          onClick: r,
          onMousedown: s,
          onKeydown: i
        }, null, 40, Fl),
        e.placeholder ? (m(), b("span", {
          key: 0,
          class: j(["textarea-input--placeholder", {
            "textarea-input--placeholder--required": e.required
          }])
        }, [
          Ne(q(e.placeholder) + " ", 1),
          e.statistics ? (m(), R(d, {
            key: 0,
            class: "ml-2"
          })) : L("", !0)
        ], 2)) : L("", !0)
      ], 2);
    };
  }
});
const Hl = /* @__PURE__ */ h("span", { class: "toggle--indicator" }, null, -1), Wl = {
  key: 0,
  class: "toggle--label"
}, ql = /* @__PURE__ */ C({
  __name: "Toggle",
  props: {
    modelValue: { type: Boolean, default: !1 },
    statistics: { type: Boolean, default: !1 },
    headless: { type: Boolean, default: !1 }
  },
  emits: ["update:modelValue"],
  setup(e, { emit: t }) {
    const n = S({
      get() {
        return e.modelValue;
      },
      set(o) {
        t("update:modelValue", o);
      }
    });
    return (o, a) => {
      const l = rt;
      return m(), b("label", {
        class: j(["toggle", {
          "toggle--statistics": e.statistics,
          "toggle--headless": e.headless
        }])
      }, [
        te(h("input", {
          "onUpdate:modelValue": a[0] || (a[0] = (r) => ot(n) ? n.value = r : null),
          class: "toggle--control",
          type: "checkbox"
        }, null, 512), [
          [Cn, B(n)]
        ]),
        Hl,
        o.$slots.default || e.statistics ? (m(), b("span", Wl, [
          P(o.$slots, "default"),
          e.statistics ? (m(), R(l, {
            key: 0,
            class: "ml-2"
          })) : L("", !0)
        ])) : L("", !0)
      ], 2);
    };
  }
});
const Kl = "data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMjQiIGhlaWdodD0iMTIyLjY0MyIgdmlld0JveD0iMCAwIDEyNCAxMjIuNjQzIj4KICA8ZyBpZD0iR3J1cHBlXzM0NSIgZGF0YS1uYW1lPSJHcnVwcGUgMzQ1IiB0cmFuc2Zvcm09InRyYW5zbGF0ZSgtNTYwIC0xNzIuNjk5KSI+CiAgICA8cmVjdCBpZD0iUmVjaHRlY2tfMjY4IiBkYXRhLW5hbWU9IlJlY2h0ZWNrIDI2OCIgd2lkdGg9IjEyNCIgaGVpZ2h0PSIxMjIuNjQzIiB0cmFuc2Zvcm09InRyYW5zbGF0ZSg1NjAgMTcyLjY5OSkiIGZpbGw9IiMyYjQ0NTIiLz4KICAgIDxnIGlkPSJHcnVwcGVfMzQzIiBkYXRhLW5hbWU9IkdydXBwZSAzNDMiIHRyYW5zZm9ybT0idHJhbnNsYXRlKDU2Ni40NTQgMTk0LjMzOCkiPgogICAgICA8cGF0aCBpZD0iUGZhZF83MyIgZGF0YS1uYW1lPSJQZmFkIDczIiBkPSJNNCw5Ni4yMjhhMzYuMjc3LDM2LjI3NywwLDAsMSw3Mi41NTUsMEg2Ny40ODVhMjcuMjA4LDI3LjIwOCwwLDEsMC01NC40MTYsMFpNNDAuMjc3LDU1LjQxNkEyNy4yMDgsMjcuMjA4LDAsMSwxLDY3LjQ4NSwyOC4yMDgsMjcuMiwyNy4yLDAsMCwxLDQwLjI3Nyw1NS40MTZabTAtOS4wNjlBMTguMTM5LDE4LjEzOSwwLDEsMCwyMi4xMzksMjguMjA4LDE4LjEzNCwxOC4xMzQsMCwwLDAsNDAuMjc3LDQ2LjM0N1oiIHRyYW5zZm9ybT0idHJhbnNsYXRlKDE0LjEzOSAzLjUzNSkiIGZpbGw9IiNmZmYiLz4KICAgIDwvZz4KICA8L2c+Cjwvc3ZnPgo=", Yl = ["src", "alt"], Gl = /* @__PURE__ */ C({
  __name: "Avatar",
  props: {
    src: { default: "" },
    alt: { default: "" }
  },
  setup(e) {
    function t(n) {
      n.target.src = Kl;
    }
    return (n, o) => (m(), b("img", {
      src: e.src,
      alt: e.alt,
      class: "avatar",
      onError: t
    }, null, 40, Yl));
  }
});
const Zl = { class: "content-card--wrapper" }, Xl = { class: "content-card--header" }, Ql = { class: "headline-5 content-card--headline" }, Jl = { class: "content-card--actions" }, er = { class: "mt-4" }, tr = /* @__PURE__ */ C({
  __name: "ContentCard",
  props: {
    title: { default: "" }
  },
  setup(e) {
    return (t, n) => (m(), b("div", Zl, [
      h("div", Xl, [
        h("h5", Ql, q(e.title), 1),
        h("div", Jl, [
          P(t.$slots, "actions")
        ])
      ]),
      h("div", er, [
        P(t.$slots, "default")
      ])
    ]));
  }
});
function zt(e, t, n) {
  var o, a, l, r, s;
  t == null && (t = 100);
  function i() {
    var c = Date.now() - r;
    c < t && c >= 0 ? o = setTimeout(i, t - c) : (o = null, n || (s = e.apply(l, a), l = a = null));
  }
  var u = function() {
    l = this, a = arguments, r = Date.now();
    var c = n && !o;
    return o || (o = setTimeout(i, t)), c && (s = e.apply(l, a), l = a = null), s;
  };
  return u.clear = function() {
    o && (clearTimeout(o), o = null);
  }, u.flush = function() {
    o && (s = e.apply(l, a), l = a = null, clearTimeout(o), o = null);
  }, u;
}
zt.debounce = zt;
var nr = zt;
const ar = { class: "headline-2 flex items-center space-x-2 text-black" }, or = { class: "mt-4" }, lr = /* @__PURE__ */ C({
  __name: "ContentSidebar",
  props: {
    size: { default: "normal" }
  },
  setup(e) {
    const t = k(null), n = k(null), o = k(0), a = k(0), l = k(0);
    K(() => {
      switch (t.value = document.querySelector("main"), e.size) {
        case "normal":
          l.value = 0.33;
          break;
        case "large":
          l.value = 0.66;
          break;
        default:
          l.value = 0.33;
      }
      r(), window.addEventListener("resize", nr.debounce(r, 100));
    }), Be(() => {
      window.removeEventListener("resize", r);
    });
    function r() {
      var i, u;
      o.value = (u = (i = t.value) == null ? void 0 : i.clientWidth) != null ? u : 0, a.value = Math.floor(
        o.value * l.value
      );
      const s = 17.5 * 13;
      a.value < s && (a.value = s), n.value && (n.value.style.width = a.value + "px");
    }
    return (s, i) => (m(), b("div", {
      id: "contentSidebar",
      ref_key: "sidebar",
      ref: n,
      class: "px-8 py-6"
    }, [
      h("h5", ar, [
        P(s.$slots, "header")
      ]),
      h("div", or, [
        P(s.$slots, "content")
      ])
    ], 512));
  }
}), rr = { class: "header--wrapper" }, sr = { class: "headline-2 flex items-center space-x-2 text-black" }, ir = /* @__PURE__ */ C({
  __name: "Header",
  props: {
    badge: { default: "" },
    badgeVariant: { default: "" },
    badgeSize: { default: "" }
  },
  setup(e) {
    return (t, n) => {
      const o = Wt;
      return m(), b("div", rr, [
        h("h5", sr, [
          P(t.$slots, "default")
        ]),
        e.badge ? (m(), R(o, {
          key: 0,
          variant: e.badgeVariant,
          size: e.badgeSize
        }, {
          default: I(() => [
            Ne(q(e.badge), 1)
          ]),
          _: 1
        }, 8, ["variant", "size"])) : L("", !0)
      ]);
    };
  }
});
function Nt(e, t, n) {
  var o, a, l, r, s;
  t == null && (t = 100);
  function i() {
    var c = Date.now() - r;
    c < t && c >= 0 ? o = setTimeout(i, t - c) : (o = null, n || (s = e.apply(l, a), l = a = null));
  }
  var u = function() {
    l = this, a = arguments, r = Date.now();
    var c = n && !o;
    return o || (o = setTimeout(i, t)), c && (s = e.apply(l, a), l = a = null), s;
  };
  return u.clear = function() {
    o && (clearTimeout(o), o = null);
  }, u.flush = function() {
    o && (s = e.apply(l, a), l = a = null, clearTimeout(o), o = null);
  }, u;
}
Nt.debounce = Nt;
var At = Nt;
function ur(e, t, n) {
  ot(e) ? J(e, (o, a) => {
    a == null || a.removeEventListener(t, n), o == null || o.addEventListener(t, n);
  }) : K(() => {
    e.addEventListener(t, n);
  }), Ut(() => {
    var o;
    (o = B(e)) === null || o === void 0 || o.removeEventListener(t, n);
  });
}
function cr(e, t) {
  const n = "pointerdown";
  return typeof window > "u" || !window ? void 0 : ur(window, n, (a) => {
    const l = B(e);
    !l || l === a.target || a.composedPath().includes(l) || t(a);
  });
}
function dr(e, t, n) {
  let o = null;
  const a = k(!1);
  K(() => {
    (e.content !== void 0 || n.value) && (a.value = !0), o = new MutationObserver(l), o.observe(t.value, {
      childList: !0,
      subtree: !0
    });
  }), Ut(() => o.disconnect()), J(n, (r) => {
    r ? a.value = !0 : a.value = !1;
  });
  const l = () => {
    e.content ? a.value = !0 : a.value = !1;
  };
  return {
    hasContent: a
  };
}
function Ke(e, t) {
  var n = e.getBoundingClientRect(), o = 1, a = 1;
  return {
    width: n.width / o,
    height: n.height / a,
    top: n.top / a,
    right: n.right / o,
    bottom: n.bottom / a,
    left: n.left / o,
    x: n.left / o,
    y: n.top / a
  };
}
function ke(e) {
  if (e == null)
    return window;
  if (e.toString() !== "[object Window]") {
    var t = e.ownerDocument;
    return t && t.defaultView || window;
  }
  return e;
}
function qt(e) {
  var t = ke(e), n = t.pageXOffset, o = t.pageYOffset;
  return {
    scrollLeft: n,
    scrollTop: o
  };
}
function tt(e) {
  var t = ke(e).Element;
  return e instanceof t || e instanceof Element;
}
function fe(e) {
  var t = ke(e).HTMLElement;
  return e instanceof t || e instanceof HTMLElement;
}
function ta(e) {
  if (typeof ShadowRoot > "u")
    return !1;
  var t = ke(e).ShadowRoot;
  return e instanceof t || e instanceof ShadowRoot;
}
function pr(e) {
  return {
    scrollLeft: e.scrollLeft,
    scrollTop: e.scrollTop
  };
}
function fr(e) {
  return e === ke(e) || !fe(e) ? qt(e) : pr(e);
}
function Ce(e) {
  return e ? (e.nodeName || "").toLowerCase() : null;
}
function Le(e) {
  return ((tt(e) ? e.ownerDocument : e.document) || window.document).documentElement;
}
function Kt(e) {
  return Ke(Le(e)).left + qt(e).scrollLeft;
}
function De(e) {
  return ke(e).getComputedStyle(e);
}
function Yt(e) {
  var t = De(e), n = t.overflow, o = t.overflowX, a = t.overflowY;
  return /auto|scroll|overlay|hidden/.test(n + a + o);
}
function vr(e) {
  var t = e.getBoundingClientRect(), n = t.width / e.offsetWidth || 1, o = t.height / e.offsetHeight || 1;
  return n !== 1 || o !== 1;
}
function mr(e, t, n) {
  n === void 0 && (n = !1);
  var o = fe(t);
  fe(t) && vr(t);
  var a = Le(t), l = Ke(e), r = {
    scrollLeft: 0,
    scrollTop: 0
  }, s = {
    x: 0,
    y: 0
  };
  return (o || !o && !n) && ((Ce(t) !== "body" || Yt(a)) && (r = fr(t)), fe(t) ? (s = Ke(t), s.x += t.clientLeft, s.y += t.clientTop) : a && (s.x = Kt(a))), {
    x: l.left + r.scrollLeft - s.x,
    y: l.top + r.scrollTop - s.y,
    width: l.width,
    height: l.height
  };
}
function Gt(e) {
  var t = Ke(e), n = e.offsetWidth, o = e.offsetHeight;
  return Math.abs(t.width - n) <= 1 && (n = t.width), Math.abs(t.height - o) <= 1 && (o = t.height), {
    x: e.offsetLeft,
    y: e.offsetTop,
    width: n,
    height: o
  };
}
function It(e) {
  return Ce(e) === "html" ? e : e.assignedSlot || e.parentNode || (ta(e) ? e.host : null) || Le(e);
}
function na(e) {
  return ["html", "body", "#document"].indexOf(Ce(e)) >= 0 ? e.ownerDocument.body : fe(e) && Yt(e) ? e : na(It(e));
}
function Je(e, t) {
  var n;
  t === void 0 && (t = []);
  var o = na(e), a = o === ((n = e.ownerDocument) == null ? void 0 : n.body), l = ke(o), r = a ? [l].concat(l.visualViewport || [], Yt(o) ? o : []) : o, s = t.concat(r);
  return a ? s : s.concat(Je(It(r)));
}
function hr(e) {
  return ["table", "td", "th"].indexOf(Ce(e)) >= 0;
}
function hn(e) {
  return !fe(e) || De(e).position === "fixed" ? null : e.offsetParent;
}
function gr(e) {
  var t = navigator.userAgent.toLowerCase().indexOf("firefox") !== -1, n = navigator.userAgent.indexOf("Trident") !== -1;
  if (n && fe(e)) {
    var o = De(e);
    if (o.position === "fixed")
      return null;
  }
  for (var a = It(e); fe(a) && ["html", "body"].indexOf(Ce(a)) < 0; ) {
    var l = De(a);
    if (l.transform !== "none" || l.perspective !== "none" || l.contain === "paint" || ["transform", "perspective"].indexOf(l.willChange) !== -1 || t && l.willChange === "filter" || t && l.filter && l.filter !== "none")
      return a;
    a = a.parentNode;
  }
  return null;
}
function ct(e) {
  for (var t = ke(e), n = hn(e); n && hr(n) && De(n).position === "static"; )
    n = hn(n);
  return n && (Ce(n) === "html" || Ce(n) === "body" && De(n).position === "static") ? t : n || gr(e) || t;
}
var ve = "top", we = "bottom", _e = "right", me = "left", Zt = "auto", dt = [ve, we, _e, me], Ye = "start", nt = "end", br = "clippingParents", aa = "viewport", Xe = "popper", yr = "reference", gn = /* @__PURE__ */ dt.reduce(function(e, t) {
  return e.concat([t + "-" + Ye, t + "-" + nt]);
}, []), oa = /* @__PURE__ */ [].concat(dt, [Zt]).reduce(function(e, t) {
  return e.concat([t, t + "-" + Ye, t + "-" + nt]);
}, []), wr = "beforeRead", _r = "read", $r = "afterRead", Sr = "beforeMain", kr = "main", xr = "afterMain", Ir = "beforeWrite", Cr = "write", Pr = "afterWrite", Or = [wr, _r, $r, Sr, kr, xr, Ir, Cr, Pr];
function Ar(e) {
  var t = /* @__PURE__ */ new Map(), n = /* @__PURE__ */ new Set(), o = [];
  e.forEach(function(l) {
    t.set(l.name, l);
  });
  function a(l) {
    n.add(l.name);
    var r = [].concat(l.requires || [], l.requiresIfExists || []);
    r.forEach(function(s) {
      if (!n.has(s)) {
        var i = t.get(s);
        i && a(i);
      }
    }), o.push(l);
  }
  return e.forEach(function(l) {
    n.has(l.name) || a(l);
  }), o;
}
function Mr(e) {
  var t = Ar(e);
  return Or.reduce(function(n, o) {
    return n.concat(t.filter(function(a) {
      return a.phase === o;
    }));
  }, []);
}
function Dr(e) {
  var t;
  return function() {
    return t || (t = new Promise(function(n) {
      Promise.resolve().then(function() {
        t = void 0, n(e());
      });
    })), t;
  };
}
function Ie(e) {
  return e.split("-")[0];
}
function Er(e) {
  var t = e.reduce(function(n, o) {
    var a = n[o.name];
    return n[o.name] = a ? Object.assign({}, a, o, {
      options: Object.assign({}, a.options, o.options),
      data: Object.assign({}, a.data, o.data)
    }) : o, n;
  }, {});
  return Object.keys(t).map(function(n) {
    return t[n];
  });
}
function Br(e) {
  var t = ke(e), n = Le(e), o = t.visualViewport, a = n.clientWidth, l = n.clientHeight, r = 0, s = 0;
  return o && (a = o.width, l = o.height, /^((?!chrome|android).)*safari/i.test(navigator.userAgent) || (r = o.offsetLeft, s = o.offsetTop)), {
    width: a,
    height: l,
    x: r + Kt(e),
    y: s
  };
}
var Re = Math.max, at = Math.min, ht = Math.round;
function Tr(e) {
  var t, n = Le(e), o = qt(e), a = (t = e.ownerDocument) == null ? void 0 : t.body, l = Re(n.scrollWidth, n.clientWidth, a ? a.scrollWidth : 0, a ? a.clientWidth : 0), r = Re(n.scrollHeight, n.clientHeight, a ? a.scrollHeight : 0, a ? a.clientHeight : 0), s = -o.scrollLeft + Kt(e), i = -o.scrollTop;
  return De(a || n).direction === "rtl" && (s += Re(n.clientWidth, a ? a.clientWidth : 0) - l), {
    width: l,
    height: r,
    x: s,
    y: i
  };
}
function la(e, t) {
  var n = t.getRootNode && t.getRootNode();
  if (e.contains(t))
    return !0;
  if (n && ta(n)) {
    var o = t;
    do {
      if (o && e.isSameNode(o))
        return !0;
      o = o.parentNode || o.host;
    } while (o);
  }
  return !1;
}
function jt(e) {
  return Object.assign({}, e, {
    left: e.x,
    top: e.y,
    right: e.x + e.width,
    bottom: e.y + e.height
  });
}
function Rr(e) {
  var t = Ke(e);
  return t.top = t.top + e.clientTop, t.left = t.left + e.clientLeft, t.bottom = t.top + e.clientHeight, t.right = t.left + e.clientWidth, t.width = e.clientWidth, t.height = e.clientHeight, t.x = t.left, t.y = t.top, t;
}
function bn(e, t) {
  return t === aa ? jt(Br(e)) : fe(t) ? Rr(t) : jt(Tr(Le(e)));
}
function Lr(e) {
  var t = Je(It(e)), n = ["absolute", "fixed"].indexOf(De(e).position) >= 0, o = n && fe(e) ? ct(e) : e;
  return tt(o) ? t.filter(function(a) {
    return tt(a) && la(a, o) && Ce(a) !== "body";
  }) : [];
}
function zr(e, t, n) {
  var o = t === "clippingParents" ? Lr(e) : [].concat(t), a = [].concat(o, [n]), l = a[0], r = a.reduce(function(s, i) {
    var u = bn(e, i);
    return s.top = Re(u.top, s.top), s.right = at(u.right, s.right), s.bottom = at(u.bottom, s.bottom), s.left = Re(u.left, s.left), s;
  }, bn(e, l));
  return r.width = r.right - r.left, r.height = r.bottom - r.top, r.x = r.left, r.y = r.top, r;
}
function Ge(e) {
  return e.split("-")[1];
}
function Xt(e) {
  return ["top", "bottom"].indexOf(e) >= 0 ? "x" : "y";
}
function ra(e) {
  var t = e.reference, n = e.element, o = e.placement, a = o ? Ie(o) : null, l = o ? Ge(o) : null, r = t.x + t.width / 2 - n.width / 2, s = t.y + t.height / 2 - n.height / 2, i;
  switch (a) {
    case ve:
      i = {
        x: r,
        y: t.y - n.height
      };
      break;
    case we:
      i = {
        x: r,
        y: t.y + t.height
      };
      break;
    case _e:
      i = {
        x: t.x + t.width,
        y: s
      };
      break;
    case me:
      i = {
        x: t.x - n.width,
        y: s
      };
      break;
    default:
      i = {
        x: t.x,
        y: t.y
      };
  }
  var u = a ? Xt(a) : null;
  if (u != null) {
    var c = u === "y" ? "height" : "width";
    switch (l) {
      case Ye:
        i[u] = i[u] - (t[c] / 2 - n[c] / 2);
        break;
      case nt:
        i[u] = i[u] + (t[c] / 2 - n[c] / 2);
        break;
    }
  }
  return i;
}
function sa() {
  return {
    top: 0,
    right: 0,
    bottom: 0,
    left: 0
  };
}
function ia(e) {
  return Object.assign({}, sa(), e);
}
function ua(e, t) {
  return t.reduce(function(n, o) {
    return n[o] = e, n;
  }, {});
}
function Qt(e, t) {
  t === void 0 && (t = {});
  var n = t, o = n.placement, a = o === void 0 ? e.placement : o, l = n.boundary, r = l === void 0 ? br : l, s = n.rootBoundary, i = s === void 0 ? aa : s, u = n.elementContext, c = u === void 0 ? Xe : u, d = n.altBoundary, v = d === void 0 ? !1 : d, p = n.padding, f = p === void 0 ? 0 : p, y = ia(typeof f != "number" ? f : ua(f, dt)), g = c === Xe ? yr : Xe, _ = e.rects.popper, M = e.elements[v ? g : c], O = zr(tt(M) ? M : M.contextElement || Le(e.elements.popper), r, i), $ = Ke(e.elements.reference), E = ra({
    reference: $,
    element: _,
    strategy: "absolute",
    placement: a
  }), z = jt(Object.assign({}, _, E)), A = c === Xe ? z : $, T = {
    top: O.top - A.top + y.top,
    bottom: A.bottom - O.bottom + y.bottom,
    left: O.left - A.left + y.left,
    right: A.right - O.right + y.right
  }, w = e.modifiersData.offset;
  if (c === Xe && w) {
    var D = w[a];
    Object.keys(T).forEach(function(U) {
      var ne = [_e, we].indexOf(U) >= 0 ? 1 : -1, H = [ve, we].indexOf(U) >= 0 ? "y" : "x";
      T[U] += D[H] * ne;
    });
  }
  return T;
}
var yn = {
  placement: "bottom",
  modifiers: [],
  strategy: "absolute"
};
function wn() {
  for (var e = arguments.length, t = new Array(e), n = 0; n < e; n++)
    t[n] = arguments[n];
  return !t.some(function(o) {
    return !(o && typeof o.getBoundingClientRect == "function");
  });
}
function Nr(e) {
  e === void 0 && (e = {});
  var t = e, n = t.defaultModifiers, o = n === void 0 ? [] : n, a = t.defaultOptions, l = a === void 0 ? yn : a;
  return function(s, i, u) {
    u === void 0 && (u = l);
    var c = {
      placement: "bottom",
      orderedModifiers: [],
      options: Object.assign({}, yn, l),
      modifiersData: {},
      elements: {
        reference: s,
        popper: i
      },
      attributes: {},
      styles: {}
    }, d = [], v = !1, p = {
      state: c,
      setOptions: function(_) {
        var M = typeof _ == "function" ? _(c.options) : _;
        y(), c.options = Object.assign({}, l, c.options, M), c.scrollParents = {
          reference: tt(s) ? Je(s) : s.contextElement ? Je(s.contextElement) : [],
          popper: Je(i)
        };
        var O = Mr(Er([].concat(o, c.options.modifiers)));
        return c.orderedModifiers = O.filter(function($) {
          return $.enabled;
        }), f(), p.update();
      },
      forceUpdate: function() {
        if (!v) {
          var _ = c.elements, M = _.reference, O = _.popper;
          if (!!wn(M, O)) {
            c.rects = {
              reference: mr(M, ct(O), c.options.strategy === "fixed"),
              popper: Gt(O)
            }, c.reset = !1, c.placement = c.options.placement, c.orderedModifiers.forEach(function(D) {
              return c.modifiersData[D.name] = Object.assign({}, D.data);
            });
            for (var $ = 0; $ < c.orderedModifiers.length; $++) {
              if (c.reset === !0) {
                c.reset = !1, $ = -1;
                continue;
              }
              var E = c.orderedModifiers[$], z = E.fn, A = E.options, T = A === void 0 ? {} : A, w = E.name;
              typeof z == "function" && (c = z({
                state: c,
                options: T,
                name: w,
                instance: p
              }) || c);
            }
          }
        }
      },
      update: Dr(function() {
        return new Promise(function(g) {
          p.forceUpdate(), g(c);
        });
      }),
      destroy: function() {
        y(), v = !0;
      }
    };
    if (!wn(s, i))
      return p;
    p.setOptions(u).then(function(g) {
      !v && u.onFirstUpdate && u.onFirstUpdate(g);
    });
    function f() {
      c.orderedModifiers.forEach(function(g) {
        var _ = g.name, M = g.options, O = M === void 0 ? {} : M, $ = g.effect;
        if (typeof $ == "function") {
          var E = $({
            state: c,
            name: _,
            instance: p,
            options: O
          }), z = function() {
          };
          d.push(E || z);
        }
      });
    }
    function y() {
      d.forEach(function(g) {
        return g();
      }), d = [];
    }
    return p;
  };
}
var gt = {
  passive: !0
};
function jr(e) {
  var t = e.state, n = e.instance, o = e.options, a = o.scroll, l = a === void 0 ? !0 : a, r = o.resize, s = r === void 0 ? !0 : r, i = ke(t.elements.popper), u = [].concat(t.scrollParents.reference, t.scrollParents.popper);
  return l && u.forEach(function(c) {
    c.addEventListener("scroll", n.update, gt);
  }), s && i.addEventListener("resize", n.update, gt), function() {
    l && u.forEach(function(c) {
      c.removeEventListener("scroll", n.update, gt);
    }), s && i.removeEventListener("resize", n.update, gt);
  };
}
var Vr = {
  name: "eventListeners",
  enabled: !0,
  phase: "write",
  fn: function() {
  },
  effect: jr,
  data: {}
};
function Fr(e) {
  var t = e.state, n = e.name;
  t.modifiersData[n] = ra({
    reference: t.rects.reference,
    element: t.rects.popper,
    strategy: "absolute",
    placement: t.placement
  });
}
var Ur = {
  name: "popperOffsets",
  enabled: !0,
  phase: "read",
  fn: Fr,
  data: {}
}, Hr = {
  top: "auto",
  right: "auto",
  bottom: "auto",
  left: "auto"
};
function Wr(e) {
  var t = e.x, n = e.y, o = window, a = o.devicePixelRatio || 1;
  return {
    x: ht(ht(t * a) / a) || 0,
    y: ht(ht(n * a) / a) || 0
  };
}
function _n(e) {
  var t, n = e.popper, o = e.popperRect, a = e.placement, l = e.variation, r = e.offsets, s = e.position, i = e.gpuAcceleration, u = e.adaptive, c = e.roundOffsets, d = c === !0 ? Wr(r) : typeof c == "function" ? c(r) : r, v = d.x, p = v === void 0 ? 0 : v, f = d.y, y = f === void 0 ? 0 : f, g = r.hasOwnProperty("x"), _ = r.hasOwnProperty("y"), M = me, O = ve, $ = window;
  if (u) {
    var E = ct(n), z = "clientHeight", A = "clientWidth";
    E === ke(n) && (E = Le(n), De(E).position !== "static" && s === "absolute" && (z = "scrollHeight", A = "scrollWidth")), E = E, (a === ve || (a === me || a === _e) && l === nt) && (O = we, y -= E[z] - o.height, y *= i ? 1 : -1), (a === me || (a === ve || a === we) && l === nt) && (M = _e, p -= E[A] - o.width, p *= i ? 1 : -1);
  }
  var T = Object.assign({
    position: s
  }, u && Hr);
  if (i) {
    var w;
    return Object.assign({}, T, (w = {}, w[O] = _ ? "0" : "", w[M] = g ? "0" : "", w.transform = ($.devicePixelRatio || 1) <= 1 ? "translate(" + p + "px, " + y + "px)" : "translate3d(" + p + "px, " + y + "px, 0)", w));
  }
  return Object.assign({}, T, (t = {}, t[O] = _ ? y + "px" : "", t[M] = g ? p + "px" : "", t.transform = "", t));
}
function qr(e) {
  var t = e.state, n = e.options, o = n.gpuAcceleration, a = o === void 0 ? !0 : o, l = n.adaptive, r = l === void 0 ? !0 : l, s = n.roundOffsets, i = s === void 0 ? !0 : s, u = {
    placement: Ie(t.placement),
    variation: Ge(t.placement),
    popper: t.elements.popper,
    popperRect: t.rects.popper,
    gpuAcceleration: a
  };
  t.modifiersData.popperOffsets != null && (t.styles.popper = Object.assign({}, t.styles.popper, _n(Object.assign({}, u, {
    offsets: t.modifiersData.popperOffsets,
    position: t.options.strategy,
    adaptive: r,
    roundOffsets: i
  })))), t.modifiersData.arrow != null && (t.styles.arrow = Object.assign({}, t.styles.arrow, _n(Object.assign({}, u, {
    offsets: t.modifiersData.arrow,
    position: "absolute",
    adaptive: !1,
    roundOffsets: i
  })))), t.attributes.popper = Object.assign({}, t.attributes.popper, {
    "data-popper-placement": t.placement
  });
}
var Kr = {
  name: "computeStyles",
  enabled: !0,
  phase: "beforeWrite",
  fn: qr,
  data: {}
};
function Yr(e) {
  var t = e.state;
  Object.keys(t.elements).forEach(function(n) {
    var o = t.styles[n] || {}, a = t.attributes[n] || {}, l = t.elements[n];
    !fe(l) || !Ce(l) || (Object.assign(l.style, o), Object.keys(a).forEach(function(r) {
      var s = a[r];
      s === !1 ? l.removeAttribute(r) : l.setAttribute(r, s === !0 ? "" : s);
    }));
  });
}
function Gr(e) {
  var t = e.state, n = {
    popper: {
      position: t.options.strategy,
      left: "0",
      top: "0",
      margin: "0"
    },
    arrow: {
      position: "absolute"
    },
    reference: {}
  };
  return Object.assign(t.elements.popper.style, n.popper), t.styles = n, t.elements.arrow && Object.assign(t.elements.arrow.style, n.arrow), function() {
    Object.keys(t.elements).forEach(function(o) {
      var a = t.elements[o], l = t.attributes[o] || {}, r = Object.keys(t.styles.hasOwnProperty(o) ? t.styles[o] : n[o]), s = r.reduce(function(i, u) {
        return i[u] = "", i;
      }, {});
      !fe(a) || !Ce(a) || (Object.assign(a.style, s), Object.keys(l).forEach(function(i) {
        a.removeAttribute(i);
      }));
    });
  };
}
var Zr = {
  name: "applyStyles",
  enabled: !0,
  phase: "write",
  fn: Yr,
  effect: Gr,
  requires: ["computeStyles"]
}, Xr = [Vr, Ur, Kr, Zr], Qr = /* @__PURE__ */ Nr({
  defaultModifiers: Xr
});
function Jr(e) {
  return e === "x" ? "y" : "x";
}
function wt(e, t, n) {
  return Re(e, at(t, n));
}
function es(e) {
  var t = e.state, n = e.options, o = e.name, a = n.mainAxis, l = a === void 0 ? !0 : a, r = n.altAxis, s = r === void 0 ? !1 : r, i = n.boundary, u = n.rootBoundary, c = n.altBoundary, d = n.padding, v = n.tether, p = v === void 0 ? !0 : v, f = n.tetherOffset, y = f === void 0 ? 0 : f, g = Qt(t, {
    boundary: i,
    rootBoundary: u,
    padding: d,
    altBoundary: c
  }), _ = Ie(t.placement), M = Ge(t.placement), O = !M, $ = Xt(_), E = Jr($), z = t.modifiersData.popperOffsets, A = t.rects.reference, T = t.rects.popper, w = typeof y == "function" ? y(Object.assign({}, t.rects, {
    placement: t.placement
  })) : y, D = {
    x: 0,
    y: 0
  };
  if (!!z) {
    if (l || s) {
      var U = $ === "y" ? ve : me, ne = $ === "y" ? we : _e, H = $ === "y" ? "height" : "width", ae = z[$], re = z[$] + g[U], W = z[$] - g[ne], X = p ? -T[H] / 2 : 0, G = M === Ye ? A[H] : T[H], oe = M === Ye ? -T[H] : -A[H], Q = t.elements.arrow, Oe = p && Q ? Gt(Q) : {
        width: 0,
        height: 0
      }, Fe = t.modifiersData["arrow#persistent"] ? t.modifiersData["arrow#persistent"].padding : sa(), pt = Fe[U], ze = Fe[ne], xe = wt(0, A[H], Oe[H]), ft = O ? A[H] / 2 - X - xe - pt - w : G - xe - pt - w, Ct = O ? -A[H] / 2 + X + xe + ze + w : oe + xe + ze + w, Ue = t.elements.arrow && ct(t.elements.arrow), Pt = Ue ? $ === "y" ? Ue.clientTop || 0 : Ue.clientLeft || 0 : 0, on = t.modifiersData.offset ? t.modifiersData.offset[t.placement][$] : 0, ln = z[$] + ft - on - Pt, rn = z[$] + Ct - on;
      if (l) {
        var sn = wt(p ? at(re, ln) : re, ae, p ? Re(W, rn) : W);
        z[$] = sn, D[$] = sn - ae;
      }
      if (s) {
        var ha = $ === "x" ? ve : me, ga = $ === "x" ? we : _e, vt = z[E], un = vt + g[ha], cn = vt - g[ga], dn = wt(p ? at(un, ln) : un, vt, p ? Re(cn, rn) : cn);
        z[E] = dn, D[E] = dn - vt;
      }
    }
    t.modifiersData[o] = D;
  }
}
var ts = {
  name: "preventOverflow",
  enabled: !0,
  phase: "main",
  fn: es,
  requiresIfExists: ["offset"]
}, ns = {
  left: "right",
  right: "left",
  bottom: "top",
  top: "bottom"
};
function _t(e) {
  return e.replace(/left|right|bottom|top/g, function(t) {
    return ns[t];
  });
}
var as = {
  start: "end",
  end: "start"
};
function $n(e) {
  return e.replace(/start|end/g, function(t) {
    return as[t];
  });
}
function os(e, t) {
  t === void 0 && (t = {});
  var n = t, o = n.placement, a = n.boundary, l = n.rootBoundary, r = n.padding, s = n.flipVariations, i = n.allowedAutoPlacements, u = i === void 0 ? oa : i, c = Ge(o), d = c ? s ? gn : gn.filter(function(f) {
    return Ge(f) === c;
  }) : dt, v = d.filter(function(f) {
    return u.indexOf(f) >= 0;
  });
  v.length === 0 && (v = d);
  var p = v.reduce(function(f, y) {
    return f[y] = Qt(e, {
      placement: y,
      boundary: a,
      rootBoundary: l,
      padding: r
    })[Ie(y)], f;
  }, {});
  return Object.keys(p).sort(function(f, y) {
    return p[f] - p[y];
  });
}
function ls(e) {
  if (Ie(e) === Zt)
    return [];
  var t = _t(e);
  return [$n(e), t, $n(t)];
}
function rs(e) {
  var t = e.state, n = e.options, o = e.name;
  if (!t.modifiersData[o]._skip) {
    for (var a = n.mainAxis, l = a === void 0 ? !0 : a, r = n.altAxis, s = r === void 0 ? !0 : r, i = n.fallbackPlacements, u = n.padding, c = n.boundary, d = n.rootBoundary, v = n.altBoundary, p = n.flipVariations, f = p === void 0 ? !0 : p, y = n.allowedAutoPlacements, g = t.options.placement, _ = Ie(g), M = _ === g, O = i || (M || !f ? [_t(g)] : ls(g)), $ = [g].concat(O).reduce(function(ze, xe) {
      return ze.concat(Ie(xe) === Zt ? os(t, {
        placement: xe,
        boundary: c,
        rootBoundary: d,
        padding: u,
        flipVariations: f,
        allowedAutoPlacements: y
      }) : xe);
    }, []), E = t.rects.reference, z = t.rects.popper, A = /* @__PURE__ */ new Map(), T = !0, w = $[0], D = 0; D < $.length; D++) {
      var U = $[D], ne = Ie(U), H = Ge(U) === Ye, ae = [ve, we].indexOf(ne) >= 0, re = ae ? "width" : "height", W = Qt(t, {
        placement: U,
        boundary: c,
        rootBoundary: d,
        altBoundary: v,
        padding: u
      }), X = ae ? H ? _e : me : H ? we : ve;
      E[re] > z[re] && (X = _t(X));
      var G = _t(X), oe = [];
      if (l && oe.push(W[ne] <= 0), s && oe.push(W[X] <= 0, W[G] <= 0), oe.every(function(ze) {
        return ze;
      })) {
        w = U, T = !1;
        break;
      }
      A.set(U, oe);
    }
    if (T)
      for (var Q = f ? 3 : 1, Oe = function(xe) {
        var ft = $.find(function(Ct) {
          var Ue = A.get(Ct);
          if (Ue)
            return Ue.slice(0, xe).every(function(Pt) {
              return Pt;
            });
        });
        if (ft)
          return w = ft, "break";
      }, Fe = Q; Fe > 0; Fe--) {
        var pt = Oe(Fe);
        if (pt === "break")
          break;
      }
    t.placement !== w && (t.modifiersData[o]._skip = !0, t.placement = w, t.reset = !0);
  }
}
var ss = {
  name: "flip",
  enabled: !0,
  phase: "main",
  fn: rs,
  requiresIfExists: ["offset"],
  data: {
    _skip: !1
  }
};
function is(e, t, n) {
  var o = Ie(e), a = [me, ve].indexOf(o) >= 0 ? -1 : 1, l = typeof n == "function" ? n(Object.assign({}, t, {
    placement: e
  })) : n, r = l[0], s = l[1];
  return r = r || 0, s = (s || 0) * a, [me, _e].indexOf(o) >= 0 ? {
    x: s,
    y: r
  } : {
    x: r,
    y: s
  };
}
function us(e) {
  var t = e.state, n = e.options, o = e.name, a = n.offset, l = a === void 0 ? [0, 0] : a, r = oa.reduce(function(c, d) {
    return c[d] = is(d, t.rects, l), c;
  }, {}), s = r[t.placement], i = s.x, u = s.y;
  t.modifiersData.popperOffsets != null && (t.modifiersData.popperOffsets.x += i, t.modifiersData.popperOffsets.y += u), t.modifiersData[o] = r;
}
var cs = {
  name: "offset",
  enabled: !0,
  phase: "main",
  requires: ["popperOffsets"],
  fn: us
}, ds = function(t, n) {
  return t = typeof t == "function" ? t(Object.assign({}, n.rects, {
    placement: n.placement
  })) : t, ia(typeof t != "number" ? t : ua(t, dt));
};
function ps(e) {
  var t, n = e.state, o = e.name, a = e.options, l = n.elements.arrow, r = n.modifiersData.popperOffsets, s = Ie(n.placement), i = Xt(s), u = [me, _e].indexOf(s) >= 0, c = u ? "height" : "width";
  if (!(!l || !r)) {
    var d = ds(a.padding, n), v = Gt(l), p = i === "y" ? ve : me, f = i === "y" ? we : _e, y = n.rects.reference[c] + n.rects.reference[i] - r[i] - n.rects.popper[c], g = r[i] - n.rects.reference[i], _ = ct(l), M = _ ? i === "y" ? _.clientHeight || 0 : _.clientWidth || 0 : 0, O = y / 2 - g / 2, $ = d[p], E = M - v[c] - d[f], z = M / 2 - v[c] / 2 + O, A = wt($, z, E), T = i;
    n.modifiersData[o] = (t = {}, t[T] = A, t.centerOffset = A - z, t);
  }
}
function fs(e) {
  var t = e.state, n = e.options, o = n.element, a = o === void 0 ? "[data-popper-arrow]" : o;
  a != null && (typeof a == "string" && (a = t.elements.popper.querySelector(a), !a) || !la(t.elements.popper, a) || (t.elements.arrow = a));
}
var vs = {
  name: "arrow",
  enabled: !0,
  phase: "main",
  fn: ps,
  effect: fs,
  requires: ["popperOffsets"],
  requiresIfExists: ["preventOverflow"]
};
const Mt = (e) => parseInt(e, 10);
function ms({
  arrowPadding: e,
  emit: t,
  locked: n,
  offsetDistance: o,
  offsetSkid: a,
  placement: l,
  popperNode: r,
  triggerNode: s
}) {
  const i = Ft({
    isOpen: !1,
    popperInstance: null
  }), u = (y) => {
    var g;
    (g = i.popperInstance) === null || g === void 0 || g.setOptions((_) => ({
      ..._,
      modifiers: [..._.modifiers, {
        name: "eventListeners",
        enabled: y
      }]
    }));
  }, c = () => u(!0), d = () => u(!1), v = () => {
    !i.isOpen || (i.isOpen = !1, t("close:popper"));
  }, p = () => {
    i.isOpen || (i.isOpen = !0, t("open:popper"));
  };
  J([() => i.isOpen, l], async ([y]) => {
    y ? (await f(), c()) : d();
  });
  const f = async () => {
    await ye(), i.popperInstance = Qr(s.value, r.value, {
      placement: l.value,
      modifiers: [ts, ss, {
        name: "flip",
        enabled: !n.value
      }, vs, {
        name: "arrow",
        options: {
          padding: Mt(e.value)
        }
      }, cs, {
        name: "offset",
        options: {
          offset: [Mt(a.value), Mt(o.value)]
        }
      }]
    }), i.popperInstance.update();
  };
  return Ut(() => {
    var y;
    (y = i.popperInstance) === null || y === void 0 || y.destroy();
  }), {
    ...An(i),
    open: p,
    close: v
  };
}
const hs = {
  id: "arrow",
  "data-popper-arrow": ""
};
function gs(e, t) {
  return m(), b("div", hs);
}
function ca(e, t) {
  t === void 0 && (t = {});
  var n = t.insertAt;
  if (!(!e || typeof document > "u")) {
    var o = document.head || document.getElementsByTagName("head")[0], a = document.createElement("style");
    a.type = "text/css", n === "top" && o.firstChild ? o.insertBefore(a, o.firstChild) : o.appendChild(a), a.styleSheet ? a.styleSheet.cssText = e : a.appendChild(document.createTextNode(e));
  }
}
var bs = `
#arrow[data-v-20b7fd4a],
  #arrow[data-v-20b7fd4a]::before {
    transition: background 250ms ease-in-out;
    position: absolute;
    width: calc(10px - var(--popper-theme-border-width, 0px));
    height: calc(10px - var(--popper-theme-border-width, 0px));
    box-sizing: border-box;
    background: var(--popper-theme-background-color);
}
#arrow[data-v-20b7fd4a] {
    visibility: hidden;
}
#arrow[data-v-20b7fd4a]::before {
    visibility: visible;
    content: "";
    transform: rotate(45deg);
}

  /* Top arrow */
.popper[data-popper-placement^="top"] > #arrow[data-v-20b7fd4a] {
    bottom: -5px;
}
.popper[data-popper-placement^="top"] > #arrow[data-v-20b7fd4a]::before {
    border-right: var(--popper-theme-border-width)
      var(--popper-theme-border-style) var(--popper-theme-border-color);
    border-bottom: var(--popper-theme-border-width)
      var(--popper-theme-border-style) var(--popper-theme-border-color);
}

  /* Bottom arrow */
.popper[data-popper-placement^="bottom"] > #arrow[data-v-20b7fd4a] {
    top: -5px;
}
.popper[data-popper-placement^="bottom"] > #arrow[data-v-20b7fd4a]::before {
    border-left: var(--popper-theme-border-width)
      var(--popper-theme-border-style) var(--popper-theme-border-color);
    border-top: var(--popper-theme-border-width)
      var(--popper-theme-border-style) var(--popper-theme-border-color);
}

  /* Left arrow */
.popper[data-popper-placement^="left"] > #arrow[data-v-20b7fd4a] {
    right: -5px;
}
.popper[data-popper-placement^="left"] > #arrow[data-v-20b7fd4a]::before {
    border-right: var(--popper-theme-border-width)
      var(--popper-theme-border-style) var(--popper-theme-border-color);
    border-top: var(--popper-theme-border-width)
      var(--popper-theme-border-style) var(--popper-theme-border-color);
}

  /* Right arrow */
.popper[data-popper-placement^="right"] > #arrow[data-v-20b7fd4a] {
    left: -5px;
}
`;
ca(bs);
const Jt = {};
Jt.render = gs;
Jt.__scopeId = "data-v-20b7fd4a";
var ys = Jt;
const ws = ["onKeyup"];
var da = {
  props: {
    placement: {
      type: String,
      default: "bottom",
      validator: function(e) {
        return ["auto", "auto-start", "auto-end", "top", "top-start", "top-end", "bottom", "bottom-start", "bottom-end", "right", "right-start", "right-end", "left", "left-start", "left-end"].includes(e);
      }
    },
    disableClickAway: {
      type: Boolean,
      default: !1
    },
    offsetSkid: {
      type: String,
      default: "0"
    },
    offsetDistance: {
      type: String,
      default: "12"
    },
    hover: {
      type: Boolean,
      default: !1
    },
    show: {
      type: Boolean,
      default: null
    },
    disabled: {
      type: Boolean,
      default: !1
    },
    openDelay: {
      type: [Number, String],
      default: 0
    },
    closeDelay: {
      type: [Number, String],
      default: 0
    },
    zIndex: {
      type: [Number, String],
      default: 9999
    },
    arrow: {
      type: Boolean,
      default: !1
    },
    arrowPadding: {
      type: String,
      default: "0"
    },
    interactive: {
      type: Boolean,
      default: !0
    },
    locked: {
      type: Boolean,
      default: !1
    },
    content: {
      type: String,
      default: null
    }
  },
  emits: ["open:popper", "close:popper"],
  setup(e, {
    emit: t
  }) {
    const n = e;
    xa((G) => ({
      c81fc0a4: e.zIndex
    }));
    const o = Pn(), a = k(null), l = k(null), r = k(null), s = k(!1);
    K(() => {
      const G = o.default();
      if (G && G.length > 1)
        return console.error(`[Popper]: The <Popper> component expects only one child element at its root. You passed ${G.length} child nodes.`);
    });
    const {
      arrowPadding: i,
      closeDelay: u,
      content: c,
      disableClickAway: d,
      disabled: v,
      interactive: p,
      locked: f,
      offsetDistance: y,
      offsetSkid: g,
      openDelay: _,
      placement: M,
      show: O
    } = An(n), {
      isOpen: $,
      open: E,
      close: z
    } = ms({
      arrowPadding: i,
      emit: t,
      locked: f,
      offsetDistance: y,
      offsetSkid: g,
      placement: M,
      popperNode: l,
      triggerNode: r
    }), {
      hasContent: A
    } = dr(o, l, c), T = S(() => O.value !== null), w = S(() => v.value || !A.value), D = S(() => $.value && !w.value), U = S(() => !d.value && !T.value), ne = S(() => p.value ? `border: ${y.value}px solid transparent; margin: -${y.value}px;` : null), H = At.debounce(E, _.value), ae = At.debounce(z, u.value), re = async () => {
      w.value || T.value || (ae.clear(), H());
    }, W = async () => {
      T.value || (H.clear(), ae());
    }, X = () => {
      $.value ? W() : re();
    };
    return J([A, v], ([G, oe]) => {
      $.value && (!G || oe) && z();
    }), J($, (G) => {
      G ? s.value = !0 : At.debounce(() => {
        s.value = !1;
      }, 200);
    }), he(() => {
      T.value && (O.value ? H() : ae());
    }), he(() => {
      U.value && cr(a, W);
    }), (G, oe) => (m(), b("div", {
      class: "inline-block",
      style: On(B(ne)),
      onMouseleave: oe[2] || (oe[2] = (Q) => e.hover && W()),
      ref: (Q, Oe) => {
        Oe.popperContainerNode = Q, a.value = Q;
      }
    }, [h("div", {
      ref: (Q, Oe) => {
        Oe.triggerNode = Q, r.value = Q;
      },
      onMouseover: oe[0] || (oe[0] = (Q) => e.hover && re()),
      onClick: X,
      onFocus: re,
      onKeyup: be(W, ["esc"])
    }, [P(G.$slots, "default")], 40, ws), x(Ia, {
      name: "fade"
    }, {
      default: I(() => [te(h("div", {
        onClick: oe[1] || (oe[1] = (Q) => !B(p) && W()),
        class: "popper",
        ref: (Q, Oe) => {
          Oe.popperNode = Q, l.value = Q;
        }
      }, [P(G.$slots, "content", {
        close: B(z),
        isOpen: s.value
      }, () => [Ne(q(B(c)), 1)]), e.arrow ? (m(), R(ys, {
        key: 0
      })) : L("", !0)], 512), [[pe, B(D)]])]),
      _: 3
    })], 36));
  }
}, _s = `
.inline-block[data-v-5784ed69] {
    display: inline-block;
}
.popper[data-v-5784ed69] {
    transition: background 250ms ease-in-out;
    background: var(--popper-theme-background-color);
    padding: var(--popper-theme-padding);
    color: var(--popper-theme-text-color);
    border-radius: var(--popper-theme-border-radius);
    border-width: var(--popper-theme-border-width);
    border-style: var(--popper-theme-border-style);
    border-color: var(--popper-theme-border-color);
    box-shadow: var(--popper-theme-box-shadow);
    z-index: var(--c81fc0a4);
}
.popper[data-v-5784ed69]:hover,
  .popper:hover > #arrow[data-v-5784ed69]::before {
    background: var(--popper-theme-background-color-hover);
}
.inline-block[data-v-5784ed69] {
    display: inline-block;
}
.fade-enter-active[data-v-5784ed69],
  .fade-leave-active[data-v-5784ed69] {
    transition: opacity 0.2s ease;
}
.fade-enter-from[data-v-5784ed69],
  .fade-leave-to[data-v-5784ed69] {
    opacity: 0;
}
`;
ca(_s);
da.__scopeId = "data-v-5784ed69";
var $s = /* @__PURE__ */ (() => {
  const e = da;
  return e.install = (t) => {
    t.component("Popper", e);
  }, e;
})();
const Ss = /* @__PURE__ */ C({
  __name: "Popover",
  props: {
    placement: { default: "auto" },
    disableClickAway: { type: Boolean, default: !1 },
    offsetX: { default: "0" },
    offsetY: { default: "0" },
    openDelay: { default: 0 },
    hover: { type: Boolean, default: !0 }
  },
  setup(e) {
    return (t, n) => (m(), R(B($s), {
      hover: e.hover,
      placement: e.placement,
      "disable-click-away": e.disableClickAway,
      "offset-x": e.offsetX,
      "offset-y": e.offsetY,
      "open-delay": e.openDelay,
      arrow: ""
    }, {
      content: I(() => [
        P(t.$slots, "content", {}, void 0, !0)
      ]),
      default: I(() => [
        P(t.$slots, "trigger", {}, void 0, !0)
      ]),
      _: 3
    }, 8, ["hover", "placement", "disable-click-away", "offset-x", "offset-y", "open-delay"]));
  }
});
const pa = /* @__PURE__ */ Pe(Ss, [["__scopeId", "data-v-210fa2c1"]]), ks = {
  preserveAspectRatio: "xMidYMid meet",
  viewBox: "0 0 24 24",
  width: "1.2em",
  height: "1.2em"
}, xs = /* @__PURE__ */ h("path", {
  fill: "currentColor",
  d: "M12 3c-1.1 0-2 .9-2 2s.9 2 2 2s2-.9 2-2s-.9-2-2-2zm0 14c-1.1 0-2 .9-2 2s.9 2 2 2s2-.9 2-2s-.9-2-2-2zm0-7c-1.1 0-2 .9-2 2s.9 2 2 2s2-.9 2-2s-.9-2-2-2z"
}, null, -1), Is = [
  xs
];
function Cs(e, t) {
  return m(), b("svg", ks, Is);
}
const Ps = { name: "ri-more-2-fill", render: Cs }, Os = {
  preserveAspectRatio: "xMidYMid meet",
  viewBox: "0 0 24 24",
  width: "1.2em",
  height: "1.2em"
}, As = /* @__PURE__ */ h("path", {
  fill: "currentColor",
  d: "m13.172 12l-4.95-4.95l1.414-1.414L16 12l-6.364 6.364l-1.414-1.414z"
}, null, -1), Ms = [
  As
];
function Ds(e, t) {
  return m(), b("svg", Os, Ms);
}
const fa = { name: "ri-arrow-right-s-line", render: Ds }, Es = {
  preserveAspectRatio: "xMidYMid meet",
  viewBox: "0 0 24 24",
  width: "1.2em",
  height: "1.2em"
}, Bs = /* @__PURE__ */ h("path", {
  fill: "currentColor",
  d: "M20 4v12h3l-4 5l-4-5h3V4h2zm-8 14v2H3v-2h9zm2-7v2H3v-2h11zm0-7v2H3V4h11z"
}, null, -1), Ts = [
  Bs
];
function Rs(e, t) {
  return m(), b("svg", Es, Ts);
}
const en = { name: "ri-sort-desc", render: Rs }, Ls = {
  preserveAspectRatio: "xMidYMid meet",
  viewBox: "0 0 24 24",
  width: "1.2em",
  height: "1.2em"
}, zs = /* @__PURE__ */ h("path", {
  fill: "currentColor",
  d: "m19 3l4 5h-3v12h-2V8h-3l4-5zm-5 15v2H3v-2h11zm0-7v2H3v-2h11zm-2-7v2H3V4h9z"
}, null, -1), Ns = [
  zs
];
function js(e, t) {
  return m(), b("svg", Ls, Ns);
}
const tn = { name: "ri-sort-asc", render: js }, Vs = {
  preserveAspectRatio: "xMidYMid meet",
  viewBox: "0 0 24 24",
  width: "1.2em",
  height: "1.2em"
}, Fs = /* @__PURE__ */ h("path", {
  fill: "currentColor",
  d: "m11.95 7.95l-1.414 1.414L8 6.828V20H6V6.828L3.465 9.364L2.05 7.95L7 3l4.95 4.95zm10 8.1L17 21l-4.95-4.95l1.414-1.414l2.537 2.536L16 4h2v13.172l2.536-2.536l1.414 1.414z"
}, null, -1), Us = [
  Fs
];
function Hs(e, t) {
  return m(), b("svg", Vs, Us);
}
const nn = { name: "ri-arrow-up-down-line", render: Hs }, Ws = C({
  name: "SvwsUiTable",
  props: {
    arrows: {
      type: Boolean,
      default: !1
    },
    border: {
      type: Boolean,
      default: !0
    },
    multiSelect: {
      type: Boolean,
      default: !1
    },
    cols: {
      type: Array,
      default: () => []
    },
    rows: {
      type: Array,
      default: () => []
    },
    actions: {
      type: Array,
      default: () => []
    },
    selected: {
      type: Object
    },
    footer: {
      type: Boolean,
      default: !1
    }
  },
  emits: [
    "update:sort",
    "update:asc",
    "update:selected",
    "action",
    "update:selectedItems"
  ],
  data() {
    return {
      sort: "",
      asc: !0,
      sorting: {
        column: "",
        asc: !1
      },
      current: {
        data: {},
        selected: !1
      },
      items: {
        data: {},
        selected: !1
      },
      selectedItems: [],
      slot_open: !1
    };
  },
  computed: {},
  watch: {
    sort() {
      this.sorting.column = this.sort;
    },
    asc() {
      this.sorting.asc = this.asc;
    },
    items: {
      deep: !0,
      handler() {
        this.current.data.id === void 0 && this.items[0] !== void 0 && this.changeCurrent(this.items[0]);
      }
    },
    rows: {
      deep: !0,
      handler() {
        this.updateData();
      }
    },
    sorting: {
      deep: !0,
      handler() {
        this.doSort();
      }
    }
  },
  created() {
    this.updateData();
  },
  mounted() {
    var e;
    (e = document.getElementById("row_1")) == null || e.focus();
  },
  methods: {
    open_slot() {
      this.slot_open = !this.slot_open;
    },
    changeSort(e) {
      e.sortable && (this.sorting.column === e.id ? (this.sorting.asc = !this.sorting.asc, this.$emit("update:asc", this.sorting.asc)) : (this.sorting.column = e.id, this.sorting.asc = !0, this.$emit("update:sort", this.sorting.column), this.$emit("update:asc", this.sorting.asc)));
    },
    doSort() {
      this.sorting.column && (this.sorting.asc ? this.items.sort(
        (e, t) => t.data[this.sorting.column].toString().localeCompare(
          e.data[this.sorting.column].toString(),
          "de-DE"
        )
      ) : this.items.sort(
        (e, t) => e.data[this.sorting.column].toString().localeCompare(
          t.data[this.sorting.column].toString(),
          "de-DE"
        )
      ));
    },
    selectAll() {
      this.selectedItems.length >= 0 && this.selectedItems.length < this.items.length ? (this.items.forEach((e) => e.selected = !0), this.selectedItems = this.items) : this.selectedItems.length === this.items.length && (this.items.forEach((e) => e.selected = !1), this.selectedItems = []), this.$emit("update:selectedItems", this.selectedItems);
    },
    mousePressed(e) {
      this.changeCurrent(e);
    },
    toggleSelect(e) {
      e.selected = !e.selected, e.selected ? this.selectedItems.push(e) : this.selectedItems = this.removeFromArray(
        this.selectedItems,
        e
      ), this.$emit("update:selectedItems", this.selectedItems);
    },
    updateData() {
      this.items = this.rows.map((e) => ({
        data: e,
        selected: !1
      }));
    },
    removeFromArray(e, t) {
      return e.filter(function(n) {
        return n != t;
      });
    },
    changeCurrent(e) {
      if (!!e)
        return this.current = e, this.$emit("update:selected", e.data), e.data;
    },
    onKeyDown() {
      event == null || event.preventDefault();
      let e = null;
      const t = this.items.indexOf(this.current);
      t + 1 === this.items.length ? (this.changeCurrent(this.items[0]), e = document.getElementById("row_1")) : (this.changeCurrent(this.items[t + 1]), e = document.getElementById("row_" + (t + 1))), this.focusAndScroll(e);
    },
    onKeyDownSpace() {
      event == null || event.preventDefault(), this.multiSelect && this.toggleSelect(this.current);
    },
    onKeyUp() {
      event == null || event.preventDefault();
      let e = null;
      const t = this.items.indexOf(this.current);
      t === 0 ? (this.changeCurrent(this.items[this.items.length - 1]), e = document.getElementById(
        "row_" + this.items.length
      )) : (this.changeCurrent(this.items[t - 1]), e = document.getElementById("row_" + t)), this.focusAndScroll(e);
    },
    focusAndScroll(e) {
      e.focus(), e.scrollIntoView({
        behavior: "smooth",
        block: "center",
        inline: "nearest"
      });
    }
  }
});
const qs = { class: "table" }, Ks = { class: "table--header" }, Ys = {
  key: 0,
  class: "table--cell table--cell-padded w-1"
}, Gs = ["width", "onClick"], Zs = { class: "table--header-col" }, Xs = { class: "table--header-col--text" }, Qs = { key: 0 }, Js = {
  key: 1,
  class: "table--cell table--cell-padded"
}, ei = ["id", "tabindex"], ti = ["width", "onClick"], ni = { class: "flex" }, ai = { class: "table--action-button" }, oi = { class: "table--action-items" }, li = {
  key: 0,
  class: "table--footer-wrapper"
}, ri = {
  class: "table--footer-row",
  colspan: "1000"
}, si = { class: "table--footer" }, ii = { class: "table--footer--actions" };
function ui(e, t, n, o, a, l) {
  const r = nn, s = ie, i = tn, u = en, c = St, d = st, v = fa, p = Ps, f = lt, y = pa;
  return m(), b("table", qs, [
    h("thead", Ks, [
      h("tr", null, [
        e.multiSelect ? (m(), b("td", Ys)) : L("", !0),
        (m(!0), b(Z, null, ue(e.cols, (g) => (m(), b("td", {
          key: g.id,
          class: "table--cell table--cell-padded",
          width: g.width,
          onClick: (_) => e.changeSort(g)
        }, [
          h("div", Zs, [
            h("span", Xs, q(g.title), 1),
            g.sortable ? (m(), b("span", Qs, [
              te(x(s, null, {
                default: I(() => [
                  x(r)
                ]),
                _: 2
              }, 1536), [
                [pe, g.id !== e.sorting.column]
              ]),
              te(x(s, null, {
                default: I(() => [
                  x(i)
                ]),
                _: 2
              }, 1536), [
                [
                  pe,
                  e.sorting.asc && g.id === e.sorting.column
                ]
              ]),
              te(x(s, null, {
                default: I(() => [
                  x(u)
                ]),
                _: 2
              }, 1536), [
                [
                  pe,
                  !e.sorting.asc && g.id === e.sorting.column
                ]
              ])
            ])) : L("", !0)
          ])
        ], 8, Gs))), 128)),
        e.actions && e.actions.length > 0 ? (m(), b("td", Js)) : L("", !0)
      ])
    ]),
    h("tbody", null, [
      (m(!0), b(Z, null, ue(e.items, (g) => (m(), b(Z, {
        key: g.data.id
      }, [
        h("tr", {
          id: "row_" + (e.items.indexOf(g) + 1),
          tabindex: e.items.indexOf(g) + 1,
          class: j(["table--row", {
            "table--row-selected": e.current.data.id === g.data.id
          }]),
          onKeydown: [
            t[0] || (t[0] = be((..._) => e.onKeyDownSpace && e.onKeyDownSpace(..._), ["space"])),
            t[1] || (t[1] = be((..._) => e.onKeyDown && e.onKeyDown(..._), ["down"])),
            t[2] || (t[2] = be((..._) => e.onKeyUp && e.onKeyUp(..._), ["up"]))
          ]
        }, [
          e.multiSelect ? (m(), b("td", {
            key: 0,
            class: j(["table--cell table--cell-padded", { "table--border": e.border }])
          }, [
            x(c, {
              "model-value": g.selected,
              onChange: (_) => e.toggleSelect(g)
            }, null, 8, ["model-value", "onChange"])
          ], 2)) : L("", !0),
          (m(!0), b(Z, null, ue(e.cols, (_) => (m(), b("td", {
            key: g.data[_.id],
            class: j(["table--cell table--cell-padded", { "table--border": e.border }]),
            width: _.width,
            onClick: (M) => e.mousePressed(g)
          }, [
            h("div", ni, [
              e.current === g && e.arrows && _ === e.cols[0] ? (m(), b(Z, { key: 0 }, [
                e.slot_open ? (m(), R(d, {
                  key: 0,
                  class: "inline-flex",
                  onClick: e.open_slot
                }, null, 8, ["onClick"])) : (m(), R(v, {
                  key: 1,
                  class: "inline-flex",
                  onClick: e.open_slot
                }, null, 8, ["onClick"]))
              ], 64)) : L("", !0),
              Ne(" " + q(g.data[_.id]), 1)
            ])
          ], 10, ti))), 128)),
          e.actions && e.actions.length > 0 ? (m(), b("td", {
            key: 1,
            class: j(["table--cell", { "table--border": e.border }])
          }, [
            x(y, {
              hover: !1,
              placement: "left-end",
              "disable-click-away": !1
            }, {
              trigger: I(() => [
                h("button", ai, [
                  x(s, null, {
                    default: I(() => [
                      x(p)
                    ]),
                    _: 1
                  })
                ])
              ]),
              content: I(() => [
                h("div", oi, [
                  (m(!0), b(Z, null, ue(e.actions, (_) => (m(), b("div", { key: _ }, [
                    x(f, {
                      class: "table--action-item",
                      type: "transparent",
                      onClick: (M) => e.$emit("action", [
                        _.action,
                        g
                      ])
                    }, {
                      default: I(() => [
                        Ne(q(_.label), 1)
                      ]),
                      _: 2
                    }, 1032, ["onClick"])
                  ]))), 128))
                ])
              ]),
              _: 2
            }, 1024)
          ], 2)) : L("", !0)
        ], 42, ei),
        e.current === g && e.slot_open ? P(e.$slots, "tr", { key: 0 }) : L("", !0)
      ], 64))), 128))
    ]),
    h("tfoot", null, [
      e.multiSelect || e.footer ? (m(), b("tr", li, [
        h("td", ri, [
          h("div", si, [
            e.multiSelect ? (m(), R(c, {
              key: 0,
              "model-value": e.selectedItems === e.items,
              onChange: t[3] || (t[3] = (g) => e.selectAll())
            }, null, 8, ["model-value"])) : L("", !0),
            h("div", ii, [
              e.footer ? P(e.$slots, "footer", { key: 0 }) : L("", !0)
            ])
          ])
        ])
      ])) : L("", !0)
    ])
  ]);
}
const ci = /* @__PURE__ */ Pe(Ws, [["render", ui]]), di = C({
  name: "SvwsUiTableInput",
  props: {
    tableId: {
      type: String,
      default: "table" + Date.now()
    },
    autofocus: {
      type: Boolean,
      default: !0
    },
    border: {
      type: Boolean,
      default: !0
    },
    multiSelect: {
      type: Boolean,
      default: !1
    },
    cols: {
      type: Array,
      default: () => []
    },
    rows: {
      type: Array,
      default: () => []
    },
    selected: {
      type: Object
    },
    footer: {
      type: Boolean,
      default: !1
    }
  },
  emits: [
    "update:sort",
    "update:asc",
    "update:selected",
    "update:selectedItems"
  ],
  data() {
    return {
      sort: "",
      asc: !0,
      sorting: {
        column: "",
        asc: !1
      },
      current: {
        data: {},
        selected: !1
      },
      items: {
        data: {},
        selected: !1
      },
      selectedItems: []
    };
  },
  watch: {
    current() {
    },
    sort() {
      this.sorting.column = this.sort;
    },
    asc() {
      this.sorting.asc = this.asc;
    },
    items: {
      deep: !0,
      handler() {
        this.current.data.id === void 0 && this.items[0] !== void 0 && this.autofocus && this.changeCurrent(this.items[0]);
      }
    },
    rows: {
      deep: !0,
      handler() {
        this.updateData();
      }
    },
    sorting: {
      deep: !0,
      handler() {
        this.doSort();
      }
    }
  },
  created() {
    this.updateData();
  },
  mounted() {
    var e;
    this.autofocus && ((e = document.getElementById(this.tableId + "_row_1")) == null || e.focus());
  },
  methods: {
    log(e) {
      console.log(e);
    },
    changeSort(e) {
      e.sortable && (this.sorting.column === e.id ? (this.sorting.asc = !this.sorting.asc, this.$emit("update:asc", this.sorting.asc)) : (this.sorting.column = e.id, this.sorting.asc = !0, this.$emit("update:sort", this.sorting.column), this.$emit("update:asc", this.sorting.asc)));
    },
    doSort() {
      this.sorting.column && (this.sorting.asc ? this.items.sort(
        (e, t) => t.data[this.sorting.column].toString().localeCompare(
          e.data[this.sorting.column].toString(),
          "de-DE"
        )
      ) : this.items.sort(
        this.items.sort(
          (e, t) => -e.data[this.sorting.column].toString().localeCompare(
            t.data[this.sorting.column].toString(),
            "de-DE"
          )
        )
      ));
    },
    selectAll() {
      this.selectedItems.length >= 0 && this.selectedItems.length < this.items.length ? (this.items.forEach((e) => e.selected = !0), this.selectedItems = this.items) : this.selectedItems.length === this.items.length && (this.items.forEach((e) => e.selected = !1), this.selectedItems = []), this.$emit("update:selectedItems", this.selectedItems);
    },
    mousePressed(e) {
      this.changeCurrent(e);
    },
    toggleSelect(e) {
      e.selected = !e.selected, e.selected ? this.selectedItems.push(e) : this.selectedItems = this.removeFromArray(
        this.selectedItems,
        e
      ), this.$emit("update:selectedItems", this.selectedItems);
    },
    updateData() {
      this.items = this.rows.map((e) => ({
        data: e,
        selected: !1
      }));
    },
    removeFromArray(e, t) {
      return e.filter(function(n) {
        return n != t;
      });
    },
    changeCurrent(e) {
      if (!!e)
        return this.current = e, this.$emit("update:selected", e.data), e.data;
    },
    onKeyDown() {
      event == null || event.preventDefault();
      let e = null;
      const t = this.items.indexOf(this.current);
      t + 1 === this.items.length ? (this.changeCurrent(this.items[0]), e = document.getElementById(this.tableId + "_row_1")) : (this.changeCurrent(this.items[t + 1]), e = document.getElementById(
        this.tableId + "_row_" + (t + 1)
      )), this.focusAndScroll(e);
    },
    onKeyDownSpace() {
      event == null || event.preventDefault(), this.multiSelect && this.toggleSelect(this.current);
    },
    onKeyUp() {
      event == null || event.preventDefault();
      let e = null;
      const t = this.items.indexOf(this.current);
      t === 0 ? (this.changeCurrent(this.items[this.items.length - 1]), e = document.getElementById(
        this.tableId + "_row_" + this.items.length
      )) : (this.changeCurrent(this.items[t - 1]), e = document.getElementById(
        this.tableId + "_row_" + t
      )), this.focusAndScroll(e);
    },
    focusAndScroll(e) {
      e.focus(), e.scrollIntoView({
        behavior: "smooth",
        block: "center",
        inline: "nearest"
      });
    }
  }
});
const pi = ["id"], fi = { class: "table--header" }, vi = {
  key: 0,
  class: "table--cell w-1"
}, mi = ["width", "onClick"], hi = { class: "table--header-col" }, gi = { class: "table--header-col--text" }, bi = { key: 0 }, yi = ["id", "tabindex"], wi = ["width", "onClick"], _i = ["id", "name", "value", "placeholder"], $i = ["id", "name", "value", "placeholder"], Si = { key: 4 }, ki = { key: 0 };
function xi(e, t, n, o, a, l) {
  const r = nn, s = ie, i = tn, u = en, c = St, d = ea;
  return m(), b("table", {
    id: e.tableId,
    class: "table"
  }, [
    h("thead", fi, [
      h("tr", null, [
        e.multiSelect ? (m(), b("td", vi)) : L("", !0),
        (m(!0), b(Z, null, ue(e.cols, (v) => (m(), b("td", {
          key: v.id,
          class: "table--cell",
          width: v.width,
          onClick: (p) => e.changeSort(v)
        }, [
          h("div", hi, [
            h("span", gi, q(v.title), 1),
            v.sortable ? (m(), b("span", bi, [
              te(x(s, null, {
                default: I(() => [
                  x(r)
                ]),
                _: 2
              }, 1536), [
                [pe, v.id !== e.sorting.column]
              ]),
              te(x(s, null, {
                default: I(() => [
                  x(i)
                ]),
                _: 2
              }, 1536), [
                [
                  pe,
                  e.sorting.asc && v.id === e.sorting.column
                ]
              ]),
              te(x(s, null, {
                default: I(() => [
                  x(u)
                ]),
                _: 2
              }, 1536), [
                [
                  pe,
                  !e.sorting.asc && v.id === e.sorting.column
                ]
              ])
            ])) : L("", !0)
          ])
        ], 8, mi))), 128))
      ])
    ]),
    h("tbody", null, [
      (m(!0), b(Z, null, ue(e.items, (v) => (m(), b("tr", {
        id: e.tableId + "_row_" + (e.items.indexOf(v) + 1),
        key: v.data.id,
        tabindex: e.items.indexOf(v) + 1,
        class: j({ "table--row-selected": e.current === v }),
        onKeydown: [
          t[0] || (t[0] = be((...p) => e.onKeyDownSpace && e.onKeyDownSpace(...p), ["space"])),
          t[1] || (t[1] = be((...p) => e.onKeyDown && e.onKeyDown(...p), ["down"])),
          t[2] || (t[2] = be((...p) => e.onKeyUp && e.onKeyUp(...p), ["up"]))
        ]
      }, [
        e.multiSelect ? (m(), b("td", {
          key: 0,
          class: j(["table--cell", { "table--border": e.border }])
        }, [
          x(c, {
            "model-value": v.selected,
            onChange: (p) => e.toggleSelect(v)
          }, null, 8, ["model-value", "onChange"])
        ], 2)) : L("", !0),
        (m(!0), b(Z, null, ue(e.cols, (p) => (m(), b("td", {
          key: v.data[p.id],
          class: j(["table--cell", {
            "table--border": e.border
          }]),
          width: p.width,
          onClick: (f) => e.mousePressed(v)
        }, [
          p.type === "checkbox" ? (m(), R(c, {
            key: 0,
            id: e.tableId + "_item.data.id",
            modelValue: v.data[p.id],
            "onUpdate:modelValue": (f) => v.data[p.id] = f,
            name: e.tableId + "_item.data.id"
          }, null, 8, ["id", "modelValue", "onUpdate:modelValue", "name"])) : p.type === "number" ? (m(), b("input", {
            key: 1,
            id: e.tableId + "_" + v.data.id,
            type: "number",
            name: e.tableId + "_" + p.id,
            value: v.data[p.id],
            placeholder: p.placeholder
          }, null, 8, _i)) : p.type === "text" ? (m(), b("input", {
            key: 2,
            id: e.tableId + "_" + v.data.id,
            type: "text",
            name: e.tableId + "_" + p.id,
            value: v.data[p.id],
            placeholder: p.placeholder
          }, null, 8, $i)) : p.type === "multiselect" ? (m(), R(d, {
            key: 3,
            id: e.tableId + "_" + v.data.id,
            "model-value": v.data[p.id],
            inline: !0,
            items: p.args.items,
            "item-text": p.args.itemText
          }, null, 8, ["id", "model-value", "items", "item-text"])) : (m(), b("p", Si, q(v.data[p.id]), 1))
        ], 10, wi))), 128))
      ], 42, yi))), 128))
    ]),
    e.footer || e.multiSelect ? (m(), b("tfoot", ki, [
      h("tr", null, [
        h("td", null, [
          h("div", null, [
            e.multiSelect ? (m(), R(c, {
              key: 0,
              "model-value": e.selectedItems === e.items,
              onChange: t[3] || (t[3] = (v) => e.selectAll())
            }, null, 8, ["model-value"])) : L("", !0),
            h("div", null, [
              e.footer ? P(e.$slots, "footer", { key: 0 }) : L("", !0)
            ])
          ])
        ])
      ])
    ])) : L("", !0)
  ], 8, pi);
}
const Ii = /* @__PURE__ */ Pe(di, [["render", xi]]);
function Ci(e) {
  const t = e.reduce((n, o) => ({ ...n, ...o }), {});
  return Object.keys(t);
}
function Pi(e) {
  return e.charAt(0).toUpperCase() + e.slice(1);
}
const va = (e) => {
  var n, o;
  const t = typeof e == "string" ? { key: e } : e;
  return {
    key: t.key,
    label: t.label || Pi(t.key),
    sortable: (n = t.sortable) != null ? n : !1,
    defaultSort: (o = t.defaultSort) != null ? o : null
  };
}, Oi = (e) => Ci(e).map((t) => va(t)), Ai = (e) => e.map((t) => va(t));
function Mi(e, t) {
  return {
    columnsComputed: S(() => t.length === 0 ? Oi(e) : Ai(t))
  };
}
const Di = {
  key: 0,
  class: "w-1"
}, Ei = { class: "table__head-content" }, Bi = { key: 1 }, Ti = { key: 0 }, Ri = { class: "table__cell-content" }, Li = { class: "table__cell-content" }, zi = { colspan: "1000" }, Ni = { class: "v-table__footer" }, ji = { class: "v-table__footer--actions" }, Vi = /* @__PURE__ */ C({
  __name: "NewTable",
  props: {
    data: { default: () => [] },
    columns: { default: () => [] },
    selectionMode: { default: null },
    modelValue: { default: () => [] },
    footer: { type: Boolean, default: !1 }
  },
  emits: ["update:modelValue"],
  setup(e, { emit: t }) {
    const { columnsComputed: n } = Mi(e.data, e.columns), o = k();
    function a(l) {
      var r, s, i;
      l(), t("update:modelValue", (i = (s = (r = o.value) == null ? void 0 : r.tableState) == null ? void 0 : s.selectedRows) != null ? i : []);
    }
    return K(() => o.value.selectRows(e.modelValue)), J(() => e.modelValue, (l) => o.value.selectRows(l)), (l, r) => {
      const s = St, i = nn, u = ie, c = tn, d = en, v = yt("VTh"), p = yt("VTr"), f = yt("VTable");
      return m(), R(f, {
        ref_key: "tableRef",
        ref: o,
        data: e.data,
        "selection-mode": e.selectionMode,
        "select-on-click": e.selectionMode ? !1 : null,
        "hide-sort-icons": ""
      }, Ca({
        head: I(({ allRowsSelected: y, toggleAllRows: g }) => [
          h("tr", null, [
            e.selectionMode ? (m(), b("th", Di, [
              h("span", Ei, [
                e.selectionMode === "multiple" ? (m(), R(s, {
                  key: 0,
                  "model-value": y,
                  "onUpdate:modelValue": (_) => a(g)
                }, null, 8, ["model-value", "onUpdate:modelValue"])) : L("", !0)
              ])
            ])) : L("", !0),
            (m(!0), b(Z, null, ue(B(n), (_, M) => P(l.$slots, `head-${_.key}`, {
              key: `head-${M}`,
              column: _
            }, () => [
              _.sortable ? (m(), R(v, {
                key: 0,
                "sort-key": _.key,
                "default-sort": _.defaultSort
              }, {
                default: I(({ sortOrder: O }) => [
                  h("div", null, [
                    h("span", null, q(_.label), 1),
                    h("span", null, [
                      te(x(u, null, {
                        default: I(() => [
                          x(i)
                        ]),
                        _: 2
                      }, 1536), [
                        [pe, O === 0]
                      ]),
                      te(x(u, null, {
                        default: I(() => [
                          x(c)
                        ]),
                        _: 2
                      }, 1536), [
                        [
                          pe,
                          O === 1
                        ]
                      ]),
                      te(x(u, null, {
                        default: I(() => [
                          x(d)
                        ]),
                        _: 2
                      }, 1536), [
                        [
                          pe,
                          O === -1
                        ]
                      ])
                    ])
                  ])
                ]),
                _: 2
              }, 1032, ["sort-key", "default-sort"])) : (m(), b("th", Bi, [
                h("span", null, q(_.label), 1)
              ]))
            ])), 128))
          ])
        ]),
        body: I(({ rows: y }) => [
          (m(!0), b(Z, null, ue(y, (g, _) => (m(), R(p, {
            key: `row-${_}`,
            row: g
          }, {
            default: I(({ isSelected: M, toggle: O }) => [
              e.selectionMode ? (m(), b("td", Ti, [
                h("span", Ri, [
                  x(s, {
                    "model-value": M === g,
                    "onUpdate:modelValue": ($) => a(O)
                  }, null, 8, ["model-value", "onUpdate:modelValue"])
                ])
              ])) : L("", !0),
              (m(!0), b(Z, null, ue(B(n), ($, E) => (m(), b("td", {
                key: `row-column-${$.key}-${E}`
              }, [
                P(l.$slots, `cell-${$.key}`, {
                  column: $,
                  row: g
                }, () => [
                  h("span", Li, q(g[$.key]), 1)
                ])
              ]))), 128))
            ]),
            _: 2
          }, 1032, ["row"]))), 128))
        ]),
        _: 2
      }, [
        e.selectionMode === "multiple" || e.footer ? {
          name: "foot",
          fn: I(({ allRowsSelected: y, toggleAllRows: g }) => [
            h("tr", null, [
              h("td", zi, [
                h("div", Ni, [
                  e.selectionMode === "multiple" ? (m(), R(s, {
                    key: 0,
                    "model-value": y,
                    onChange: g
                  }, null, 8, ["model-value", "onChange"])) : L("", !0),
                  h("div", ji, [
                    e.footer ? P(l.$slots, "footer", { key: 0 }) : L("", !0)
                  ])
                ])
              ])
            ])
          ]),
          key: "0"
        } : void 0
      ]), 1032, ["data", "selection-mode", "select-on-click"]);
    };
  }
});
const Fi = {}, Ui = { class: "overlay" };
function Hi(e, t) {
  return m(), b("div", Ui);
}
const ma = /* @__PURE__ */ Pe(Fi, [["render", Hi]]), Wi = { class: "modal--pageWrapper" }, qi = { class: "modal--titlebar" }, Ki = { class: "modal-content" }, Yi = { class: "modal--actions" }, Gi = /* @__PURE__ */ C({
  __name: "Modal",
  props: {
    size: { default: "small" }
  },
  setup(e, { expose: t }) {
    const n = k(!1);
    function o() {
      n.value = !1;
    }
    function a() {
      n.value = !0;
    }
    return t({
      openModal: a,
      closeModal: o
    }), (l, r) => {
      const s = ma, i = Qn, u = ie, c = lt;
      return m(), R(B(Po), {
        open: n.value,
        class: "modal--wrapper",
        onClose: o
      }, {
        default: I(() => [
          h("div", Wi, [
            x(s, { onClick: o }),
            h("div", {
              class: j(["modal", {
                "modal--sm": e.size === "small",
                "modal--md": e.size === "medium",
                "modal--lg": e.size === "large"
              }])
            }, [
              h("div", qi, [
                x(B(Oo), { class: "modal--title" }, {
                  default: I(() => [
                    P(l.$slots, "modalTitle")
                  ]),
                  _: 3
                }),
                x(c, {
                  class: "modal--closeButton",
                  onClick: o
                }, {
                  default: I(() => [
                    x(u, { class: "modal--closeIcon" }, {
                      default: I(() => [
                        x(i)
                      ]),
                      _: 1
                    })
                  ]),
                  _: 1
                })
              ]),
              x(B(Ao), { class: "modal--description" }, {
                default: I(() => [
                  P(l.$slots, "modalDescription")
                ]),
                _: 3
              }),
              h("div", Ki, [
                P(l.$slots, "modalContent")
              ]),
              h("div", Yi, [
                P(l.$slots, "modalActions")
              ])
            ], 2)
          ])
        ]),
        _: 3
      }, 8, ["open"]);
    };
  }
});
const Zi = {}, Xi = { class: "app-layout--wrapper" }, Qi = { class: "app-layout--sidebar-wrapper" }, Ji = /* @__PURE__ */ h("div", { class: "app-layout--pattern-wrapper" }, [
  /* @__PURE__ */ h("div", { class: "app-layout--pattern-overlay" }),
  /* @__PURE__ */ h("div", { class: "app-layout--pattern" })
], -1);
function eu(e, t) {
  return m(), b("div", Xi, [
    h("div", Qi, [
      P(e.$slots, "sidebar")
    ]),
    P(e.$slots, "secondaryMenu"),
    P(e.$slots, "main", {}, () => [
      Ji
    ]),
    P(e.$slots, "contentSidebar")
  ]);
}
const tu = /* @__PURE__ */ Pe(Zi, [["render", eu]]), nu = {
  preserveAspectRatio: "xMidYMid meet",
  viewBox: "0 0 24 24",
  width: "1.2em",
  height: "1.2em"
}, au = /* @__PURE__ */ h("path", {
  fill: "currentColor",
  d: "m10.828 12l4.95 4.95l-1.414 1.414L8 12l6.364-6.364l1.414 1.414z"
}, null, -1), ou = [
  au
];
function lu(e, t) {
  return m(), b("svg", nu, ou);
}
const ru = { name: "ri-arrow-left-s-line", render: lu }, su = { class: "sidebar--menu--header" }, iu = { class: "sidebar--menu--body" }, uu = { class: "sidebar--menu--footer" }, cu = /* @__PURE__ */ C({
  __name: "Menu",
  props: {
    collapsed: { type: Boolean, default: !1 }
  },
  emits: ["toggle"],
  setup(e, { emit: t }) {
    function n() {
      t("toggle", !e.collapsed);
    }
    return (o, a) => {
      const l = fa, r = ru, s = ie, i = lt;
      return m(), b("div", {
        class: j(["sidebar--menu", {
          "sidebar--menu--collapsed": e.collapsed
        }])
      }, [
        h("div", su, [
          P(o.$slots, "header"),
          x(i, {
            class: "sidebar--menu--collapse",
            onClick: de(n, ["prevent"])
          }, {
            default: I(() => [
              x(s, null, {
                default: I(() => [
                  e.collapsed ? (m(), R(l, { key: 0 })) : (m(), R(r, { key: 1 }))
                ]),
                _: 1
              })
            ]),
            _: 1
          }, 8, ["onClick"])
        ]),
        h("div", iu, [
          P(o.$slots, "default")
        ]),
        h("div", uu, [
          P(o.$slots, "footer")
        ])
      ], 2);
    };
  }
});
const du = ["onClick"], pu = /* @__PURE__ */ Pa('<span class="sidebar--menu-header--icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><path fill="#fdfdfc" d="M0 500V0h1000v1000H0V500z"></path><path fill="#009136" d="M0 500V0h1000v1000H0V500zm983.5 0V16.5h-967L16.2 499c-.1 265.4 0 483 .3 483.8.3 1 97.6 1.2 483.7 1l483.3-.3V500zm-564 461c-1.2-.4-3.5-3.7-5.2-7.1-7.7-15.7-5.8-29.1 5.3-37 3.8-2.8 4.4-3.7 4.4-7a11 11 0 00-2-6.3c-1.9-2.3-2.9-2.6-7.8-2.6a22 22 0 01-18-8.5c-11-12.6-11.1-38.4-.1-59.5 11.6-22.4 32.2-31.6 46.8-21 4.6 3.4 7.6 3.7 10.9 1.4 4-2.8 4.5-5 2.7-12-2.6-10.3-.3-17.7 7.8-25.1 22.7-21 73-16 84.7 8.3 2.2 4.5 2.4 10.4.4 21.5-.5 3.1-.2 3.9 2.6 6.2 3.5 3 8 3.5 10 1.1 4-4.6 13.7-6.9 21.4-4.9 7.4 2 12 5 18.8 12.4 14.8 15.9 20.9 43.7 13.8 62.6a25.4 25.4 0 01-24.2 17.5c-5.2 0-8.2 2.5-9.3 7.8-.6 3-.3 3.6 5.4 8.8 7.7 7.1 9.6 11.7 8.9 21.4-.7 8.7-6.3 21.4-10 22.4-3.5.9-165 .6-167.4-.4zM522 940.1a67.9 67.9 0 0034.5-27 63 63 0 006.1-50.7 62.9 62.9 0 00-38.6-40.1 71 71 0 00-35-1.9A63 63 0 00448 853c-9.3 19-9.3 37 0 56a62.6 62.6 0 0040.1 32 69.2 69.2 0 0033.8-.9zM496.2 919a41 41 0 01-28-21.7c-2.4-5-2.7-6.9-2.7-16.2 0-9.3.3-11.2 2.6-16.1a38.7 38.7 0 0172.4 5.5 39 39 0 01-44.3 48.5zM38 958.8c0-.8 2.3-7.5 5.1-14.9S55 911.9 63 889a1354 1354 0 0133.6-88.7A439 439 0 01156.3 697a370 370 0 0149.2-47.8c23.1-18 44-31.2 100-63.5 66.5-38.3 91.3-55.7 118.7-83.4a237.6 237.6 0 0030.5-36.4c8.5-12 24-37.2 29.1-47.6 3.7-7.3 5-8.3 5.8-4.7 1 3.8-.3 86-1.5 98.9A491.7 491.7 0 01154 937a474.6 474.6 0 01-106.8 22.7c-7.9.5-9.3.4-9.3-.9zm857-8.8a492.5 492.5 0 01-282-179.4c-12.6-16.5-29.3-41.7-28.2-42.8a81 81 0 0117.3.2l54.9 3 55 3c47 2.7 48.5 2.6 61-3.2a71.9 71.9 0 0025.5-25.2c3.6-7 5-8.1 9.8-7.2 11.2 2 16 16.3 9.6 28.8-3 6-20 22.5-27.9 27-3.6 2.2-10.1 5-14.5 6.5-7.4 2.5-9.3 2.7-25 2.8-9.3 0-28.2-.6-41.8-1.4-18.8-1-25-1.1-26-.2-2.2 2.2 10.2 24.3 22.5 40.3 19.9 25.6 47.3 47 93.3 72.8 56.4 31.6 67.9 38.6 86 52.7a197 197 0 0126.5 24.4c0 1.3-2.2 1-16-2zm-510.6-11a55.3 55.3 0 01-15.1-7.7c-6.2-4-6.6-5.9-3.3-14.7 2.6-6.9 9.5-17.6 11.4-17.6.8 0 3 2.2 5 5 2 2.7 6 6.4 8.7 8.1a19 19 0 014.9 4 48.8 48.8 0 00-3.9 16.4c-.3 4.3-1 8-1.6 8-.6.2-3.3-.5-6.1-1.5zm231.3 1.3c-.4-.3-.7-3-.7-6 0-2.9-1-8.3-2.2-12l-2.2-6.7 2.9-2a51.9 51.9 0 0012.2-11.7c1-1.6 2.5-2.9 3.4-2.9 3.3 0 13.1 18.4 13.3 24.8 0 3.1-.5 4-4.9 7.4a62.4 62.4 0 01-12.7 6.8c-8.6 3.4-8.2 3.2-9.1 2.3zM411.3 791.4c-.9-2.3.5-14.7 2.4-21.6 2.3-8.1 4.2-9.3 13.6-8.4 8.3.7 18.2 3.3 19.6 5 .7.8 0 2.7-2.5 6.1-1.9 2.7-4.3 7.7-5.3 11l-1.9 6-8.8.6c-5 .4-10.2 1.2-11.7 1.8-3.8 1.5-4.6 1.4-5.4-.5zm177.2 0c-2.2-.9-7.4-1.7-11.5-1.7l-7.4-.2-2.2-6.4a52 52 0 00-5.3-10.8c-1.7-2.4-3.1-4.7-3.1-5.2 0-3.2 25.3-7.6 29.7-5.2 5 2.7 9.6 31.3 5 31-.7 0-3-.7-5.2-1.6zm-41.3-135.1a490.1 490.1 0 01-36.2-176c0-6.3 0-6.3 5.5-11.6a63.2 63.2 0 0138.8-15.7h3.5l35.1 65.4c19.3 36 35.8 66 36.6 66.6 1.8 1.5 2.6 0 5.5-10.4 7-25.1 21.3-52 40.9-76.4 4.5-5.7 8-11 8-12-.3-.8-30.7-25.3-67.7-54.3L549.8 379l-19.1-.2-19.2-.3-.3-169.4c-.2-135 0-169.6 1-170.3 1.9-1.1 311-1 312.8.2 1.3.8 1 3.5-1.4 21.2A471.3 471.3 0 00820 92l-.6 11.4-7 3.9a635.7 635.7 0 00-44.5 26.2 3505.6 3505.6 0 00-135.5 107.7 34.9 34.9 0 00-9.8 19c-.2 3.8 0 4.3 3.7 6.2 5.7 3 8.1 6.8 8.1 12.6 0 6.1-2.5 9.7-9 12.8-4.6 2.3-4.7 2.4-4 6a22.9 22.9 0 0016 18.8c3.1.9 6.7 1.3 8 1 1.2-.3 5.4-3 9.1-5.9 5.2-4 7.2-6.2 8.2-9.2 1.7-5.1 3.4-6.9 9-9.6 6.8-3.1 18.3-3 24.9.2 16 8 9.3 22.7-11.4 24.6-6.2.6-7.4 1.1-14 6-4.9 3.6-7.3 6-7.1 7.1.4 2.1 13 6.4 25.6 8.8a71.2 71.2 0 0034.7-1.7 40 40 0 0021.5-22.8c4-10 7.8-14.1 15.5-17 12.1-4.5 23.7-5 83-3.7 43 1 46 .7 56.8-5.1a61.2 61.2 0 0021.7-25l5.3-11.3c1.5-3 3.3-3.4 9.5-2.5 8.5 1.3 14.2 10.3 12.7 20-1 7.1-10 21-20 31.2a51.2 51.2 0 01-17.6 13.2 80.1 80.1 0 01-32.4 8.2c-6.6.6-12.3 1.4-12.6 1.7-.3.4-1.9 4.5-3.4 9.2a156 156 0 01-27.9 49.5c-9.4 11.5-31 32.7-50 49.4-42.3 36.7-50 43.7-62.3 56-42.7 42.5-64.2 84-68.2 132a44.7 44.7 0 01-1.7 11c-1.1 1-100 34.1-101.8 34.1-.8 0-3-4.2-5.4-9.8zM38 266.4V38h166.4c94.3 0 166.7.4 167 .9a43 43 0 01-3.5 10.7C358.3 73.2 350.2 98 333.5 154c-21.2 71.6-31.6 100.2-48.4 134a294.2 294.2 0 01-59 83c-27.8 27.8-58.2 49.3-121.2 85.6A1912.7 1912.7 0 0051.4 488 96.3 96.3 0 0139 495c-.8 0-1.1-61.8-1.1-228.5zm729.8-67c-2.6-.9-6.3-3-8.4-5-3.3-3.2-3.6-3.8-3-7.2 1-5.1 4.5-11.2 8-13.7 4.8-3.6 12.7-2.9 22.7 2.1 8.4 4.2 19.9 12.5 19.9 14.3 0 .5-2.1 2-4.8 3.3-12.8 6.5-26 8.8-34.4 6.2zm113.5-75c-1.8-.7-3.3-2-3.3-3 0-.8 1.1-5.4 2.5-10.1 6.5-22.4-.4-46.5-18.8-66-2.7-3-4.6-5.8-4.2-6.3.4-.8 16.7-1.1 51-1.1 44.4 0 50.5.2 52 1.6 1.2 1.3 1.5 4.4 1.5 18.2 0 9.2-.4 17.2-.8 17.9-.5.8-7 .3-25.5-2.4-18-2.5-25.1-3.2-26-2.4-1.2.9-1.1 2.4 0 8.3 1 4.7 1.3 11.5 1 18.8-.4 13.5-2.5 19.3-9 24.4-3.2 2.6-4.8 3-10.5 3.3-4.3.2-7.9-.2-10-1.1z"></path><path fill="#e3001b" d="M0 500V0h1000v1000H0V500zm983.5 0V16.5h-967L16.2 499c-.1 265.4 0 483 .3 483.8.3 1 97.6 1.2 483.7 1l483.3-.3V500zm-564 461c-1.2-.4-3.5-3.7-5.2-7.1-7.7-15.7-5.8-29.1 5.3-37 3.8-2.8 4.4-3.7 4.4-7a11 11 0 00-2-6.3c-1.9-2.3-2.9-2.6-7.8-2.6a22 22 0 01-18-8.5c-11-12.6-11.1-38.4-.1-59.5 11.6-22.4 32.2-31.6 46.8-21 4.6 3.4 7.6 3.7 10.9 1.4 4-2.8 4.5-5 2.7-12-2.6-10.3-.3-17.7 7.8-25.1 22.7-21 73-16 84.7 8.3 2.2 4.5 2.4 10.4.4 21.5-.5 3.1-.2 3.9 2.6 6.2 3.5 3 8 3.5 10 1.1 4-4.6 13.7-6.9 21.4-4.9 7.4 2 12 5 18.8 12.4 14.8 15.9 20.9 43.7 13.8 62.6a25.4 25.4 0 01-24.2 17.5c-5.2 0-8.2 2.5-9.3 7.8-.6 3-.3 3.6 5.4 8.8 7.7 7.1 9.6 11.7 8.9 21.4-.7 8.7-6.3 21.4-10 22.4-3.5.9-165 .6-167.4-.4zM522 940.1a67.9 67.9 0 0034.5-27 63 63 0 006.1-50.7 62.9 62.9 0 00-38.6-40.1 71 71 0 00-35-1.9A63 63 0 00448 853c-9.3 19-9.3 37 0 56a62.6 62.6 0 0040.1 32 69.2 69.2 0 0033.8-.9zm373 10a492.5 492.5 0 01-282-179.5c-12.6-16.5-29.3-41.7-28.2-42.8a81 81 0 0117.3.2l54.9 3 55 3c47 2.7 48.5 2.6 61-3.2a71.9 71.9 0 0025.5-25.2c3.6-7 5-8.1 9.8-7.2 11.2 2 16 16.3 9.6 28.8-3 6-20 22.5-27.9 27-3.6 2.2-10.1 5-14.5 6.5-7.4 2.5-9.3 2.7-25 2.8-9.3 0-28.2-.6-41.8-1.4-18.8-1-25-1.1-26-.2-2.2 2.2 10.2 24.3 22.5 40.3 19.9 25.6 47.3 47 93.3 72.8 56.4 31.6 67.9 38.6 86 52.7a197 197 0 0126.5 24.4c0 1.3-2.2 1-16-2zM547.2 656.1a490.1 490.1 0 01-36.2-176c0-6.2 0-6.2 5.5-11.5a63.2 63.2 0 0138.8-15.7h3.5l35.1 65.4c19.3 36 35.8 66 36.6 66.6 1.8 1.5 2.6 0 5.5-10.4 7-25.1 21.3-52 40.9-76.4 4.5-5.7 8-11 8-12-.3-.8-30.7-25.3-67.7-54.3L549.8 379l-19.1-.2-19.2-.3-.3-169.4c-.2-135 0-169.6 1-170.3 1.9-1.1 311-1 312.8.2 1.3.8 1 3.5-1.4 21.2A471.3 471.3 0 00820 92l-.6 11.4-7 3.9a635.7 635.7 0 00-44.5 26.2 3505.6 3505.6 0 00-135.5 107.7 34.9 34.9 0 00-9.8 19c-.2 3.8 0 4.3 3.7 6.2 5.7 3 8.1 6.8 8.1 12.6 0 6.1-2.5 9.7-9 12.8-4.6 2.3-4.7 2.4-4 6a22.9 22.9 0 0016 18.8c3.1.9 6.7 1.3 8 1 1.2-.3 5.4-3 9.1-5.9 5.2-4 7.2-6.2 8.2-9.2 1.7-5.1 3.4-6.9 9-9.6 6.8-3.1 18.3-3 24.9.2 16 8 9.3 22.7-11.4 24.6-6.2.6-7.4 1.1-14 6-4.9 3.6-7.3 6-7.1 7.1.4 2.1 13 6.4 25.6 8.8a71.2 71.2 0 0034.7-1.7 40 40 0 0021.5-22.8c4-10 7.8-14.1 15.5-17 12.1-4.5 23.7-5 83-3.7 43 1 46 .7 56.8-5.1a61.2 61.2 0 0021.7-25l5.3-11.3c1.5-3 3.3-3.4 9.5-2.5 8.5 1.3 14.2 10.3 12.7 20-1 7.1-10 21-20 31.2a51.2 51.2 0 01-17.6 13.2 80.1 80.1 0 01-32.4 8.2c-6.6.6-12.3 1.4-12.6 1.7-.3.4-1.9 4.5-3.4 9.2a156 156 0 01-27.9 49.5c-9.4 11.5-31 32.7-50 49.4-42.3 36.7-50 43.7-62.3 56-42.7 42.5-64.2 84-68.2 132a44.7 44.7 0 01-1.7 11c-1.1 1-100 34.1-101.8 34.1-.8 0-3-4.2-5.4-9.8zm220.6-456.7c-2.6-.9-6.3-3-8.4-5-3.3-3.2-3.6-3.8-3-7.2 1-5.1 4.5-11.2 8-13.7 4.8-3.6 12.7-2.9 22.7 2.1 8.4 4.2 19.9 12.5 19.9 14.3 0 .5-2.1 2-4.8 3.3-12.8 6.5-26 8.8-34.4 6.2zm113.5-75c-1.8-.7-3.3-2-3.3-3 0-.8 1.1-5.4 2.5-10.1 6.5-22.4-.4-46.5-18.8-66-2.7-3-4.6-5.8-4.2-6.3.4-.8 16.7-1.1 51-1.1 44.4 0 50.5.2 52 1.6 1.2 1.3 1.5 4.4 1.5 18.2 0 9.2-.4 17.2-.8 17.9-.5.8-7 .3-25.5-2.4-18-2.5-25.1-3.2-26-2.4-1.2.9-1.1 2.4 0 8.3 1 4.7 1.3 11.5 1 18.8-.4 13.5-2.5 19.3-9 24.4-3.2 2.6-4.8 3-10.5 3.3-4.3.2-7.9-.2-10-1.1z"></path><path fill="#020202" d="M0 500V0h1000v1000H0V500zm983.5 0V16.5h-967L16.2 499c-.1 265.4 0 483 .3 483.8.3 1 97.6 1.2 483.7 1l483.3-.3V500z"></path></svg></span>', 1), fu = { class: "sidebar--menu-header--label" }, vu = /* @__PURE__ */ C({
  __name: "MenuHeader",
  props: {
    collapsed: { type: Boolean, default: !1 }
  },
  emits: ["click"],
  setup(e, { emit: t }) {
    function n(o) {
      t("click", o);
    }
    return (o, a) => (m(), b("a", {
      class: j(["sidebar--menu-header", {
        "sidebar--menu-header--collapsed": e.collapsed
      }]),
      href: "#",
      onClick: de(n, ["prevent"])
    }, [
      pu,
      h("span", fu, [
        P(o.$slots, "default")
      ])
    ], 10, du));
  }
});
const mu = ["onClick"], hu = { class: "sidebar--menu-item--icon" }, gu = { class: "sidebar--menu-item--label" }, bu = {
  key: 0,
  class: "sidebar--menu-item--subline"
}, yu = /* @__PURE__ */ C({
  __name: "MenuItem",
  props: {
    active: { type: Boolean, default: !1 },
    collapsed: { type: Boolean, default: !1 },
    subline: { default: "" }
  },
  emits: ["click"],
  setup(e, { emit: t }) {
    function n(o) {
      t("click", o);
    }
    return (o, a) => {
      const l = ie;
      return m(), b("a", {
        class: j(["sidebar--menu-item", {
          "sidebar--menu-item--active": e.active,
          "sidebar--menu-item--collapsed": e.collapsed
        }]),
        href: "#",
        onClick: de(n, ["prevent"])
      }, [
        h("span", hu, [
          x(l, null, {
            default: I(() => [
              P(o.$slots, "icon")
            ]),
            _: 3
          })
        ]),
        h("span", gu, [
          P(o.$slots, "label"),
          e.subline ? (m(), b("span", bu, q(e.subline), 1)) : L("", !0)
        ])
      ], 10, mu);
    };
  }
});
const wu = {}, _u = { class: "secondary-menu" }, $u = { class: "secondary-menu--headline" }, Su = { class: "secondary-menu--header" }, ku = { class: "secondary-menu--content" };
function xu(e, t) {
  return m(), b("div", _u, [
    h("h3", $u, [
      P(e.$slots, "headline")
    ]),
    h("div", Su, [
      P(e.$slots, "header")
    ]),
    h("div", ku, [
      P(e.$slots, "content")
    ])
  ]);
}
const Iu = /* @__PURE__ */ Pe(wu, [["render", xu]]);
const Cu = {}, Pu = { class: "tooltip" };
function Ou(e, t) {
  return m(), b("div", Pu, [
    P(e.$slots, "default")
  ]);
}
const Au = /* @__PURE__ */ Pe(Cu, [["render", Ou]]), Mu = /* @__PURE__ */ Object.freeze(/* @__PURE__ */ Object.defineProperty({
  __proto__: null,
  SvwsUiButton: lt,
  SvwsUiCheckbox: St,
  SvwsUiDropdown: Vo,
  SvwsUiDropdownItem: Fo,
  SvwsUiDropdownWithAction: Uo,
  SvwsUiMultiSelect: ea,
  SvwsUiProgressBar: vl,
  SvwsUiRadioGroup: bl,
  SvwsUiRadioOption: _l,
  SvwsUiSelectInput: Il,
  SvwsUiTabBar: Nl,
  SvwsUiTabButton: jl,
  SvwsUiTabPanel: Vl,
  SvwsUiTextareaInput: Ul,
  SvwsUiTextInput: Jn,
  SvwsUiToggle: ql,
  SvwsUiAvatar: Gl,
  SvwsUiContentCard: tr,
  SvwsUiContentSidebar: lr,
  SvwsUiHeader: ir,
  SvwsUiIcon: ie,
  SvwsUiTable: ci,
  SvwsUiTableInput: Ii,
  SvwsUiNewTable: Vi,
  SvwsUiModal: Gi,
  SvwsUiOverlay: ma,
  SvwsUiAppLayout: tu,
  SvwsUiSidebarMenu: cu,
  SvwsUiSidebarMenuHeader: vu,
  SvwsUiSidebarMenuItem: yu,
  SvwsUiSecondaryMenu: Iu,
  SvwsUiBadge: Wt,
  SvwsUiTooltip: Au,
  SvwsUiPopover: pa
}, Symbol.toStringTag, { value: "Module" }));
var se, We, Du = Object.defineProperty, Eu = Object.prototype.hasOwnProperty, Sn = Object.getOwnPropertySymbols, Bu = Object.prototype.propertyIsEnumerable, kn = (e, t, n) => t in e ? Du(e, t, { enumerable: !0, configurable: !0, writable: !0, value: n }) : e[t] = n, ee = (e, t) => {
  for (var n in t || (t = {}))
    Eu.call(t, n) && kn(e, n, t[n]);
  if (Sn)
    for (var n of Sn(t))
      Bu.call(t, n) && kn(e, n, t[n]);
  return e;
};
function Vt(e, t) {
  const n = (t = (t = t.replace(/\[(\w+)\]/g, ".$1")).replace(/^\./, "")).split(".");
  let o = e;
  for (const a of n) {
    if (!(a in o))
      return;
    o = o[a];
  }
  return o;
}
function xn(e) {
  return !Array.isArray(e) && !isNaN(parseFloat(e)) && isFinite(e);
}
function Tu(e, t) {
  if (function(n) {
    return n && typeof n.custom == "function";
  }(t) && !t.custom(t.value, e))
    return !1;
  if (!function(n) {
    return Array.isArray(n.keys);
  }(t) || t.value === null || t.value === void 0 || t.value.length === 0)
    return !0;
  for (const n of t.keys) {
    const o = Vt(e, n);
    if (o != null) {
      const a = Array.isArray(t.value) ? t.value : [t.value];
      for (const l of a)
        if (t.exact) {
          if (o.toString() === l.toString())
            return !0;
        } else if (o.toString().toLowerCase().includes(l.toString().toLowerCase()))
          return !0;
    }
  }
  return !1;
}
(We = se || (se = {}))[We.DESC = -1] = "DESC", We[We.NONE = 0] = "NONE", We[We.ASC = 1] = "ASC";
class Ru {
  constructor(t) {
    this.state = Ft({ data: [], filters: {}, selectedRows: [], selectionMode: "single", selectOnClick: !0, selectedClass: "", hideSortIcons: !1, sortId: null, sortKey: null, customSort: null, sortOrder: se.NONE, currentPage: 0, pageSize: void 0, sortIconPosition: "after", sortHeaderClass: "", headlessMode: !1 }), this.emit = t, this.filteredData = S(() => this.state.data.length === 0 ? [] : Object.keys(this.state.filters).length === 0 ? this.state.data : function(o, a) {
      const l = [];
      for (const r of o) {
        let s = !0;
        for (const i of Object.keys(a))
          if (!Tu(r, a[i])) {
            s = !1;
            break;
          }
        s && l.push(r);
      }
      return l;
    }(this.state.data, this.state.filters)), this.sortedData = S(() => {
      return (this.state.sortKey || this.state.customSort) && this.state.sortOrder !== 0 ? (o = this.filteredData.value, a = this.state.sortKey, l = this.state.customSort, r = this.state.sortOrder, [...o].sort((s, i) => {
        if (typeof l == "function")
          return l(s, i, r);
        let u, c;
        if (a ? typeof a == "function" ? (u = a(s, r), c = a(i, r)) : (u = Vt(s, a), c = Vt(i, a)) : (u = null, c = null), u == null && (u = ""), c == null && (c = ""), xn(u) && xn(c))
          return (u - c) * r;
        const d = u.toString(), v = c.toString();
        return d.localeCompare(v) * r;
      })) : this.filteredData.value;
      var o, a, l, r;
    }), this.totalItems = S(() => this.filteredData.value.length), this.totalPages = S(() => {
      return this.state.pageSize ? (o = this.totalItems.value, a = this.state.pageSize, o <= a ? 1 : Math.ceil(o / a)) : 0;
      var o, a;
    }), J(this.totalPages, (o) => {
      this.emit("totalPagesChanged", o);
    }, { immediate: !0 }), this.paginationEnabled = S(() => this.state.pageSize);
    const n = S(() => this.paginationEnabled.value && this.state.currentPage > this.totalPages.value);
    J(n, (o) => {
      o && this.paginationEnabled.value && (this.state.currentPage = 1, this.emit("update:currentPage", this.state.currentPage));
    }), this.displayData = S(() => this.paginationEnabled.value ? function(o, a, l) {
      if (o.length <= a || a <= 0 || l <= 0)
        return o;
      const r = (l - 1) * a, s = r + a;
      return [...o].slice(r, s);
    }(this.sortedData.value, this.state.pageSize, this.state.currentPage) : this.sortedData.value), J(this.displayData, (o) => {
      this.emit("totalItemsChanged", o.length);
    }), this.tableState = S(() => ({ rows: this.displayData.value, rowsPrePagination: this.sortedData.value, selectedRows: this.state.selectedRows })), J(this.tableState, (o) => {
      this.emit("stateChanged", o);
    }, { immediate: !0, deep: !0 });
  }
  revealItem(t) {
    if (!this.paginationEnabled.value)
      return !1;
    let n;
    return n = typeof t == "function" ? this.sortedData.value.findIndex(t) : this.sortedData.value.indexOf(t), n !== -1 && (this.emit("update:currentPage", Math.ceil((n + 1) / this.state.pageSize)), !0);
  }
  selectRow(t) {
    this.state.selectionMode !== "single" ? this.state.selectedRows.includes(t) || this.state.selectedRows.push(t) : this.state.selectedRows = [t];
  }
  selectRows(t) {
    for (const n of t)
      this.selectRow(n);
  }
  deselectRow(t) {
    const n = this.state.selectedRows.indexOf(t);
    n > -1 && this.state.selectedRows.splice(n, 1);
  }
  deselectRows(t) {
    for (const n of t)
      this.deselectRow(n);
  }
  selectAll() {
    this.state.selectionMode !== "single" && (this.state.selectedRows = [...this.state.data]);
  }
  deselectAll() {
    this.state.selectedRows = [];
  }
  setSort({ sortKey: t, customSort: n, sortOrder: o, sortId: a }) {
    this.state.sortKey = t, this.state.customSort = n, this.state.sortOrder = o, this.state.sortId = a;
  }
  syncProp(t, n, o = !1) {
    J(n, () => {
      this.state[t] = n.value;
    }, { immediate: !0, deep: o });
  }
}
const an = Symbol("store-key");
var In = C({ name: "VTable", props: { data: { type: Array, required: !0 }, filters: { type: Object, required: !1, default: () => ({}) }, currentPage: { type: Number, required: !1, default: void 0 }, pageSize: { type: Number, required: !1, default: void 0 }, selectionMode: { type: String, required: !1, default: "single", validator: (e) => ["single", "multiple"].includes(e) }, selectedClass: { required: !1, type: String, default: "vt-selected" }, selectOnClick: { required: !1, type: Boolean, default: !0 }, hideSortIcons: { required: !1, type: Boolean, default: !1 }, sortIconPosition: { required: !1, type: String, default: "after" }, sortHeaderClass: { type: String, required: !1, default: "" }, headlessMode: { type: Boolean, required: !1, default: !1 } }, emits: { stateChanged: (e) => !0, totalPagesChanged: (e) => !0, totalItemsChanged: (e) => !0 }, setup(e, t) {
  const n = new Ru(t.emit);
  Ee(an, n), n.syncProp("data", ge(e, "data")), n.syncProp("filters", ge(e, "filters"), !0), n.syncProp("currentPage", ge(e, "currentPage")), n.syncProp("pageSize", ge(e, "pageSize")), n.syncProp("selectionMode", ge(e, "selectionMode")), n.syncProp("selectedClass", ge(e, "selectedClass")), n.syncProp("selectOnClick", ge(e, "selectOnClick")), n.syncProp("hideSortIcons", ge(e, "hideSortIcons")), n.syncProp("sortIconPosition", ge(e, "sortIconPosition")), n.syncProp("sortHeaderClass", ge(e, "sortHeaderClass")), n.syncProp("headlessMode", ge(e, "headlessMode"));
  const o = S(() => n.state.selectedRows.length === n.state.data.length);
  return { store: n, tableState: n.tableState, allRowsSelected: o, toggleAllRows: () => o.value ? n.deselectAll() : n.selectAll(), selectAll: () => n.selectAll(), deselectAll: () => n.deselectAll(), selectRows: (a) => n.selectRows(a), selectRow: (a) => n.selectRow(a), deselectRows: (a) => n.deselectRows(a), deselectRow: (a) => n.deselectRow(a), revealItem: (a) => n.revealItem(a), slots: t.slots };
}, render() {
  return N("table", { class: "v-table" }, [N("thead", this.slots.head ? this.slots.head({ rows: this.tableState.rows, selectedRows: this.tableState.selectedRows, toggleAllRows: this.toggleAllRows, selectAll: this.selectAll, deselectAll: this.deselectAll, allRowsSelected: this.allRowsSelected }) : void 0), N("tbody", this.slots.body ? this.slots.body({ rows: this.tableState.rows, selectedRows: this.tableState.selectedRows, selectRow: this.selectRow, deselectRow: this.deselectRow }) : void 0), N("tfoot", this.slots.foot ? this.slots.foot({ rows: this.tableState.rows, selectedRows: this.tableState.selectedRows, toggleAllRows: this.toggleAllRows, selectAll: this.selectAll, deselectAll: this.deselectAll, allRowsSelected: this.allRowsSelected }) : void 0)]);
} });
function bt(e) {
  var t;
  const n = { width: 16, height: 16, xmlns: "http://www.w3.org/2000/svg", viewBox: `0 0 ${e.vbWidth} ${e.vbHeight}` }, o = { fill: "currentColor", d: e.d, opacity: (t = e.opacity) != null ? t : 1 };
  return N("svg", ee(ee({ attrs: n }, n), { style: ee({}, e.disabled ? { color: "#9CA3AF" } : {}) }), [N("path", ee({ attrs: o }, o))]);
}
var Lu = C({ name: "VTh", props: { sortKey: { type: [String, Function], required: !1, default: null }, customSort: { type: [Function, Object], required: !1, default: null }, defaultSort: { type: String, required: !1, validator: (e) => ["asc", "desc", null].includes(e), default: null } }, emits: ["defaultSort", "sortChanged"], setup(e, { emit: t, slots: n }) {
  const o = $e(an);
  if (!e.sortKey && !e.customSort)
    throw new Error("Must provide the Sort Key value or a Custom Sort function.");
  const a = "_" + Math.random().toString(36).substr(2, 9), l = k(se.NONE);
  K(() => {
    e.defaultSort && (l.value = e.defaultSort === "desc" ? se.DESC : se.ASC, o.setSort({ sortOrder: l.value, sortKey: e.sortKey, customSort: e.customSort, sortId: a }), ye(() => {
      t("defaultSort"), t("sortChanged", { sortOrder: l.value });
    }));
  });
  const r = S(() => {
    if (!o.state.hideSortIcons)
      return function(u) {
        const c = { width: 16, height: 16, xmlns: "http://www.w3.org/2000/svg", viewBox: "0 0 320 512" }, d = () => ({ fill: "currentColor", opacity: u === se.NONE || u === se.ASC ? 0.4 : 1, d: "M41.05 288.05h238c21.4 0 32.1 25.9 17 41l-119 119a23.9 23.9 0 0 1-33.8.1l-.1-.1-119.1-119c-15.05-15.05-4.4-41 17-41z" }), v = () => ({ fill: "currentColor", opacity: u === se.NONE || u === se.DESC ? 0.4 : 1, d: "M24.05 183.05l119.1-119A23.9 23.9 0 0 1 177 64a.94.94 0 0 1 .1.1l119 119c15.1 15.1 4.4 41-17 41h-238c-21.45-.05-32.1-25.95-17.05-41.05z" });
        return N("svg", ee({ attrs: c }, c), [N("g", [N("path", ee({ attrs: d() }, d())), N("path", ee({ attrs: v() }, v()))])]);
      }(l.value);
  });
  J(() => o.state.sortId, () => {
    o.state.sortId !== a && l.value !== 0 && (l.value = 0);
  });
  const s = () => {
    [se.DESC, se.NONE].includes(l.value) ? l.value = se.ASC : l.value = se.DESC, o.setSort({ sortOrder: l.value, sortKey: e.sortKey, customSort: e.customSort, sortId: a }), t("sortChanged", { sortOrder: l.value });
  }, i = S(() => {
    const u = [];
    return o.state.sortIconPosition !== "before" || o.state.hideSortIcons || u.push(r.value), n.default && u.push(N("span", [n.default({ sortOrder: l.value })])), o.state.sortIconPosition !== "after" || o.state.hideSortIcons || u.push(r.value), u;
  });
  return () => N("th", ee({ class: "v-th" }, { onClick: s }), [N("div", { class: o.state.sortHeaderClass }, i.value)]);
} }), zu = C({ name: "VTr", props: { row: { type: Object, required: !0 } }, setup(e, { slots: t }) {
  const n = $e(an), o = S(() => n.state.selectedRows.find((s) => s === e.row)), a = S(() => o.value ? n.state.selectedClass : ""), l = S(() => ee({}, n.state.selectOnClick ? { cursor: "pointer" } : {})), r = (s) => {
    const i = s.target;
    i && i.tagName.toLowerCase() === "td" && (o.value ? n.deselectRow(e.row) : n.selectRow(e.row));
  };
  return () => N("tr", ee(ee({ class: a.value, style: l.value }, n.state.selectOnClick ? { onClick: r } : {}), { on: ee({}, n.state.selectOnClick ? { click: r } : {}) }), t.default ? t.default({ isSelected: o.value, toggle: () => o.value ? n.deselectRow(e.row) : n.selectRow(e.row) }) : []);
} }), Nu = C({ name: "VTPagination", props: { currentPage: { type: Number, required: !0 }, totalPages: { type: Number, required: !0 }, hideSinglePage: { required: !1, type: Boolean, default: !0 }, maxPageLinks: { required: !1, type: Number, default: NaN }, boundaryLinks: { required: !1, type: Boolean, default: !1 }, directionLinks: { required: !1, type: Boolean, default: !0 } }, setup(e, { slots: t, emit: n }) {
  const o = S(() => isNaN(e.maxPageLinks) || e.maxPageLinks <= 0 ? (() => {
    const c = [];
    for (let d = 1; d <= e.totalPages; d++)
      c.push({ title: d.toString(), value: d });
    return c;
  })() : (() => {
    const c = [], d = Math.ceil(e.totalPages / e.maxPageLinks), v = Math.ceil((e.currentPage || 1) / e.maxPageLinks);
    let p = (v - 1) * e.maxPageLinks + 1;
    const f = Math.min(p + e.maxPageLinks - 1, e.totalPages), y = f - p + 1, g = e.maxPageLinks - y;
    v === d && v > 1 && g > 0 && (p -= g), v > 1 && c.push({ title: "...", value: p - 1 });
    for (let _ = p; _ <= f && !(_ > e.totalPages); _++)
      c.push({ title: _.toString(), value: _ });
    return v < d && c.push({ title: "...", value: f + 1 }), c;
  })()), a = (c) => {
    c < 1 || c > e.totalPages || c === e.currentPage || n("update:currentPage", c);
  }, l = () => {
    e.currentPage ? e.currentPage < e.totalPages && n("update:currentPage", e.currentPage + 1) : n("update:currentPage", 1);
  }, r = () => {
    e.currentPage ? e.currentPage > 1 && n("update:currentPage", e.currentPage - 1) : n("update:currentPage", 1);
  }, s = () => {
    n("update:currentPage", 1);
  }, i = () => {
    n("update:currentPage", e.totalPages);
  }, u = (c, d, v, p = !1) => N("li", { class: ["page-item", { disabled: v, active: p }] }, [N("a", ee(ee({ class: "page-link", style: ee({}, v ? { cursor: "not-allowed" } : {}), attrs: { href: "javascript:void(0)" }, href: "javascript:void(0)" }, v ? {} : { onClick: d }), { on: ee({}, v ? {} : { click: d }) }), [c])]);
  return () => {
    var c, d, v, p, f, y, g, _;
    if (e.hideSinglePage && e.totalPages === 1)
      return N("");
    const M = [];
    if (e.boundaryLinks) {
      const O = bt({ vbWidth: 512, vbHeight: 512, d: "M34.5 239L228.9 44.7c9.4-9.4 24.6-9.4 33.9 0l22.7 22.7c9.4 9.4 9.4 24.5 0 33.9L131.5 256l154 154.7c9.3 9.4 9.3 24.5 0 33.9l-22.7 22.7c-9.4 9.4-24.6 9.4-33.9 0L34.5 273c-9.3-9.4-9.3-24.6 0-34zm192 34l194.3 194.3c9.4 9.4 24.6 9.4 33.9 0l22.7-22.7c9.4-9.4 9.4-24.5 0-33.9L323.5 256l154-154.7c9.3-9.4 9.3-24.5 0-33.9l-22.7-22.7c-9.4-9.4-24.6-9.4-33.9 0L226.5 239c-9.3 9.4-9.3 24.6 0 34z" }), $ = e.currentPage === 1, E = (d = (c = t.firstPage) == null ? void 0 : c.call(t, { disabled: $ })) != null ? d : O;
      M.push(u(E, s, $));
    }
    if (e.directionLinks) {
      const O = bt({ vbWidth: 320, vbHeight: 512, d: "M34.52 239.03L228.87 44.69c9.37-9.37 24.57-9.37 33.94 0l22.67 22.67c9.36 9.36 9.37 24.52.04 33.9L131.49 256l154.02 154.75c9.34 9.38 9.32 24.54-.04 33.9l-22.67 22.67c-9.37 9.37-24.57 9.37-33.94 0L34.52 272.97c-9.37-9.37-9.37-24.57 0-33.94z" }), $ = e.currentPage === 1, E = (p = (v = t.previous) == null ? void 0 : v.call(t, { disabled: $ })) != null ? p : O;
      M.push(u(E, r, $));
    }
    for (const O of o.value)
      M.push(u(O.title, () => a(O.value), !1, O.value === e.currentPage));
    if (e.directionLinks) {
      const O = bt({ vbWidth: 320, vbHeight: 512, d: "M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z" }), $ = e.currentPage === e.totalPages, E = (y = (f = t.next) == null ? void 0 : f.call(t, { disabled: $ })) != null ? y : O;
      M.push(u(E, l, $));
    }
    if (e.boundaryLinks) {
      const O = bt({ vbWidth: 512, vbHeight: 512, d: "M477.5 273L283.1 467.3c-9.4 9.4-24.6 9.4-33.9 0l-22.7-22.7c-9.4-9.4-9.4-24.5 0-33.9l154-154.7-154-154.7c-9.3-9.4-9.3-24.5 0-33.9l22.7-22.7c9.4-9.4 24.6-9.4 33.9 0L477.5 239c9.3 9.4 9.3 24.6 0 34zm-192-34L91.1 44.7c-9.4-9.4-24.6-9.4-33.9 0L34.5 67.4c-9.4 9.4-9.4 24.5 0 33.9l154 154.7-154 154.7c-9.3 9.4-9.3 24.5 0 33.9l22.7 22.7c9.4 9.4 24.6 9.4 33.9 0L285.5 273c9.3-9.4 9.3-24.6 0-34z" }), $ = e.currentPage === e.totalPages, E = (_ = (g = t.lastPage) == null ? void 0 : g.call(t, { disabled: $ })) != null ? _ : O;
      M.push(u(E, i, $));
    }
    return N("nav", { class: "vt-pagination" }, [N("ul", { class: "pagination" }, [M])]);
  };
} }), ju = { install(e, t = {}) {
  ["hideSortIcons", "sortIconPosition", "sortHeaderClass"].forEach((n) => {
    t.hasOwnProperty(n) && (In.props[n].default = t[n]);
  }), e.component("VTable", In), e.component("VTh", Lu), e.component("VTr", zu), e.component("VTPagination", Nu);
} };
const Fu = function(t) {
  t.use(ju), Object.entries(Mu).forEach(([n, o]) => {
    t.component(n, o);
  });
};
export {
  Fu as default
};
