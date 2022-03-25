<template>
  <transition name="modal" appear>
    <div class="modal modal-overlay" @click.self="$emit('close')">
      <div class="modal-window">
        <div class="card" style="margin-bottom: 35px;
    margin-top: 15%;
    width: 600px;
    margin-left: auto;
    margin-right: auto;">
          <slot/>
        </div>
      </div>
    </div>
  </transition>
</template>

<script>
export default {
  name: 'Modal',
}
</script>

