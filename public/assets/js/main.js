// let love = document.querySelectorAll(".love");
// let heart = document.querySelectorAll(".fa-heart");

// love.forEach((love, i) => {
//   love.addEventListener("click", () => {
//     heart[i].classList.toggle("fa-regular");
//     heart[i].classList.toggle("fa-solid");
//   });
// });



const toastTrigger = document.getElementById('liveToastBtn')
const toastLiveExample = document.getElementById('liveToast')

if (toastTrigger) {
  const toastBootstrap = bootstrap.Toast.getOrCreateInstance(toastLiveExample)
  toastTrigger.addEventListener('click', () => {
    toastBootstrap.show()
  })
}

(function () {
  const track = document.getElementById('newCollectionTrack')
  if (!track) return

  const wrap = track.closest('.collection-carousel-wrap')
  const prev = document.querySelector('.collection-ctrl-prev')
  const next = document.querySelector('.collection-ctrl-next')
  const slides = () => [...track.querySelectorAll('.collection-slide')]
  const isRtl = () => document.documentElement.getAttribute('dir') === 'rtl'
  const reduceMotion = () => window.matchMedia('(prefers-reduced-motion: reduce)').matches
  const EDGE = 28

  const slideDistance = () => {
    const slide = track.querySelector('.collection-slide')
    if (!slide) return track.clientWidth * 0.8
    const styles = window.getComputedStyle(track)
    const gap = parseFloat(styles.columnGap || styles.gap) || 0
    return slide.getBoundingClientRect().width + gap
  }

  const maxScroll = () => Math.max(0, track.scrollWidth - track.clientWidth)

  const scrollOffset = () => {
    const max = maxScroll()
    const sl = track.scrollLeft
    if (!isRtl()) return { pos: sl, max }
    if (sl < 0) return { pos: Math.abs(sl), max }
    return { pos: sl > max ? 0 : sl, max }
  }

  const ends = () => {
    const items = slides()
    if (!items.length) return { start: true, end: true }
    const { pos, max } = scrollOffset()
    if (max <= EDGE) return { start: true, end: true }
    const t = track.getBoundingClientRect()
    const first = items[0].getBoundingClientRect()
    const last = items[items.length - 1].getBoundingClientRect()
    if (isRtl()) {
      return {
        start: pos <= EDGE || first.right <= t.right + EDGE,
        end: pos >= max - EDGE || last.left >= t.left - EDGE,
      }
    }
    return {
      start: pos <= EDGE && first.left >= t.left - EDGE,
      end: pos >= max - EDGE || last.right <= t.right + EDGE,
    }
  }

  const setCtrlState = (btn, disabled) => {
    if (!btn) return
    btn.classList.toggle('is-disabled', disabled)
    btn.toggleAttribute('disabled', disabled)
    btn.setAttribute('aria-disabled', disabled ? 'true' : 'false')
  }

  const updateArrows = () => {
    const { start, end } = ends()
    setCtrlState(prev, start)
    setCtrlState(next, end)
  }

  const scrollTrack = (direction) => {
    const delta = slideDistance() * direction * (isRtl() ? -1 : 1)
    track.scrollBy({ left: delta, behavior: 'smooth' })
  }

  const scrollToStart = () => {
    const { max } = scrollOffset()
    const left = isRtl() && track.scrollLeft < 0 ? 0 : (isRtl() ? max : 0)
    const first = track.querySelector('.collection-slide')
    if (first) {
      first.scrollIntoView({ inline: 'start', block: 'nearest', behavior: 'smooth' })
      return
    }
    track.scrollTo({ left: left, behavior: 'smooth' })
  }

  let autoTimer = null
  let paused = false
  let resumeTimer = null

  const stopAuto = () => {
    if (autoTimer) {
      clearInterval(autoTimer)
      autoTimer = null
    }
  }

  const startAuto = () => {
    stopAuto()
    if (reduceMotion() || slides().length < 2) return
    autoTimer = setInterval(() => {
      if (paused) return
      if (ends().end) {
        scrollToStart()
      } else {
        scrollTrack(1)
      }
    }, 4000)
  }

  const pauseAuto = () => {
    paused = true
  }

  const resumeAutoSoon = () => {
    paused = true
    if (resumeTimer) clearTimeout(resumeTimer)
    resumeTimer = setTimeout(() => {
      paused = false
      startAuto()
    }, 4000)
  }

  prev?.addEventListener('click', (event) => {
    event.preventDefault()
    if (prev.classList.contains('is-disabled')) return
    scrollTrack(-1)
    resumeAutoSoon()
  })
  next?.addEventListener('click', (event) => {
    event.preventDefault()
    if (next.classList.contains('is-disabled')) return
    scrollTrack(1)
    resumeAutoSoon()
  })

  track.addEventListener('scroll', () => {
    window.requestAnimationFrame(updateArrows)
  }, { passive: true })
  window.addEventListener('resize', updateArrows)

  if (wrap) {
    wrap.addEventListener('mouseenter', pauseAuto)
    wrap.addEventListener('mouseleave', () => {
      paused = false
    })
    wrap.addEventListener('focusin', pauseAuto)
    wrap.addEventListener('focusout', (event) => {
      if (!wrap.contains(event.relatedTarget)) paused = false
    })
  }

  updateArrows()
  startAuto()
})()


