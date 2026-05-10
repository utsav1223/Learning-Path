## Phase 2 Validation Checklist

### Architecture Integrity

**Props Management**
- [ ] No component has more than 6 props
- [ ] All props are semantic (not boolean flags like `showIcon`, `hideLabel`)
- [ ] No layout control props passed to components (grid, margin, col-span, etc.)
- [ ] Variant pattern used for color/size variations (not individual props)

**Responsive Logic**
- [ ] All responsive behavior is inside components
- [ ] Parent pages have NO `md:`, `lg:`, `xl:` classes on component usage
- [ ] Each component manages its own tablet/desktop behavior
- [ ] No breakpoint logic in page templates

**Layout Ownership**
- [ ] Dashboard only contains data passing and section arrangement
- [ ] Sidebar fully owns its responsive behavior (sticky, nav, goal summary)
- [ ] Step navigation fully owns tablet 4-col grid, desktop hide
- [ ] Skill cards fully own padding, shadows, rounded corners

---

### Component Testing

**Navigation Component**
- [ ] Tablet nav displays as 4-column grid (grid-cols-4)
- [ ] Active step shows blue dot and text
- [ ] Inactive steps show gray dot
- [ ] Touch targets are at least 48px tall
- [ ] Desktop view: properly hidden (xl:hidden)
- [ ] All buttons have data-step-jump attributes (0-3)

**Skill Level Cards**
- [ ] All 3 cards (Beginner/Intermediate/Advanced) rendering
- [ ] Padding correct: mobile 4px, tablet p-6
- [ ] Shadows correct: mobile shadow-sm, tablet shadow-xl
- [ ] Level bar shows correct fill (1, 2, or 3 segments)
- [ ] Icon SVG rendering properly
- [ ] Checked state: blue border-left, gradient background, lifted transform
- [ ] Hover state: -2px translateY, scale(1.015)

**Dashboard Sidebar**
- [ ] Mobile: Sticky at top, full width
- [ ] Desktop: Sticky left sidebar, min-h-screen
- [ ] Goal summary: Hidden on mobile/tablet, visible on desktop
- [ ] Navigation links: Correct active state (bg-white/10)
- [ ] Logout button: Mobile in main, desktop in sidebar

**Cards & Badges**
- [ ] All cards have consistent rounded corners per viewport
- [ ] Badge colors working (blue, emerald, slate)
- [ ] Hover states on milestone cards (border lift, shadow)
- [ ] Metric cards showing label and value correctly

---

### Responsive Breakpoint Testing

**Key Transitions**
- [ ] Mobile → Tablet (640px): Navigation appears, layout shifts
- [ ] Tablet → Desktop (1024px): Sidebar appears, grid changes
- [ ] Ultrawide (1920px+): Content doesn't stretch excessively, max-width holds

**Device-Specific Tests**
- [ ] iPad Air Landscape (1180px): Navigation renders correctly, no overlap
- [ ] Surface Duo Unfolded (2159px wide): Layout stable, sidebar proportion correct
- [ ] Surface Duo Folded (540px): Single column works, touch targets adequate
- [ ] iPad Mini (768px): Typography readable, spacing proportional

---

### Visual Consistency

**Colors**
- [ ] All card backgrounds consistent
- [ ] Text hierarchy maintained (bold headings, semibold labels, regular body)
- [ ] Dark sidebar text is readable (white/slate-300)
- [ ] Alert backgrounds (success/warning/error) rendering correctly

**Spacing**
- [ ] Gap between cards consistent (gap-3 md:gap-4)
- [ ] Padding consistent: p-5 md:p-6 or p-4 md:p-5
- [ ] Section margins consistent (mt-6)
- [ ] No unexpected whitespace gaps

**Shadows & Borders**
- [ ] Tablet shadows stronger: md:shadow-xl
- [ ] Borders consistent: border border-slate-200
- [ ] Glass effect visible: bg-white/80 backdrop-blur-xl

---

### Interactive States

**Focus States**
- [ ] All buttons have visible focus outline
- [ ] Links have focus states
- [ ] Form inputs show focus border (focus:border-blue-500)
- [ ] Focus outline color is blue-500 or blue-600

**Hover States**
- [ ] Buttons lift on hover (-translate-y-0.5 or -translate-y-1)
- [ ] Milestone cards: border-blue-200 on hover
- [ ] Links: color change on hover

**Active States**
- [ ] Radio buttons: checked styling applied
- [ ] Nav links: active styling visible
- [ ] Step navigation: current step highlighted
- [ ] Selection visual feedback clear

---

### Touch & Mobile UX

**Touch Targets**
- [ ] All buttons minimum 48px × 48px
- [ ] Form inputs: adequate height (py-2.5 minimum)
- [ ] Spacing between clickable elements (gap-2 minimum)
- [ ] No accidental overlaps

**Scrolling Behavior**
- [ ] Sidebar sticky scroll works on mobile
- [ ] Navigation scrolling smooth (no jank)
- [ ] Modal/overflow scrolling isolated

**Readability**
- [ ] Text sizes readable on mobile (text-sm minimum for body)
- [ ] Contrast sufficient (white/dark backgrounds)
- [ ] Line length reasonable on all widths

---

### Accessibility

**Keyboard Navigation**
- [ ] Tab order logical (nav → form fields → buttons)
- [ ] All interactive elements keyboard accessible
- [ ] Focus visible on all elements

**Screen Reader**
- [ ] Form labels properly associated
- [ ] Button text clear (not just icons)
- [ ] Icons have aria-hidden if decorative
- [ ] Role attributes present where needed

**Motion**
- [ ] Animations respect `prefers-reduced-motion`
- [ ] No flashing or strobing effects
- [ ] Transitions smooth but not distracting

---

### Performance Notes

- [ ] CSS files (surfaces.css, motion.css) load without errors
- [ ] No console errors from component rendering
- [ ] Build process completes successfully
- [ ] CSS selectors are specific enough (no unintended overrides)

---

## Sign-Off

Once all boxes checked, architecture is stable for Phase 3 (goal cards + preview integration).

If issues found:
1. Document which component/test failed
2. Note if it's layout leakage, prop explosion, or responsive logic problem
3. Fix before continuing to Phase 3
