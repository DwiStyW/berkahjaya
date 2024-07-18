"use strict";
// DOM
function $(query) {
    return document.querySelector(query);
}
const inputs = {
    title: $("#input-title"),
    text: $("#input-text"),
    icon: $("#select-icon"),
    image: $("#select-image")
}
const buttons = {
    send: $("#send-button"),
    sound: $("#sound-button"),
    soundIcons: {
        mute: $(".mute-icon"),
        unmute: $(".unmute-icon"),
    }
}

// events
buttons.sound.addEventListener("click", checkSoundStatus);
buttons.send.addEventListener("click", startNewNotification);

// events actions
function checkSoundStatus() {
    switch (soundStatus) {
        case true:
            soundStatus = false;
            buttons.soundIcons.mute.classList.remove("d-none");
            buttons.soundIcons.unmute.classList.add("d-none");
            break;
        case false:
            soundStatus = true;
            buttons.soundIcons.mute.classList.add("d-none");
            buttons.soundIcons.unmute.classList.remove("d-none");
            break;
    }
}

let soundStatus = true;

function startNewNotification() {
    let title = inputs.title.value || "Title";
    let body = inputs.text.value || "Text";
    let icon = inputs.icon.value;
    let image = inputs.image.value;
    let sound = !soundStatus;
    switch (icon) {
        case "instagram":
            icon = "http://upir.ir/images/y7jccu9dfj3tx5zdjzb.png";
            break;
        case "facebook":
            icon = "http://upir.ir/images/nxmq6fu8msipbsogx05w.png";
            break;
        default:
            icon = "";
            break;
    }

    switch (image) {
        case "plain":
            image = "http://upir.ir/images/9kp100j959bwvqo3p6.jpg";
            break;
        default:
            image = "";
            break;
    }
    createNotification({
        title,
        body,
        icon,
        image,
        silent: sound,
        dir: "auto",
    })
}

// check notification permission
const notificationPermission = new Promise((response) => {
    if ("Notification" in window) {
        if (Notification.permission === "granted") {
            response({
                status: "success",
                text: "user accepted the notifications",
            })
        } else {
            Notification.requestPermission()
                .then(permission => {
                    if (permission === "granted") {
                        response({
                            status: "success",
                            text: "user accepted the notifications",
                        })
                    } else {
                        response({
                            status: "error",
                            text: "User did not accept notifications",
                        })
                    }
                })
        }
    } else {
        response({
            status: "error",
            text: "This Browser does not support desktop notification !",
        })
    }
})

let userPermission;
async function checkNotificationPermission() {
    const permission = await notificationPermission;
    if (permission.status === "success") {
        userPermission = true;
    } else {
        console.warn("User did not accept notifications !");
        userPermission = false;
    }
}
checkNotificationPermission();

// show notification
let notif;

function createNotification(data) {
    if (notif) {
        notif.close();
    }
    notif = new Notification(data.title, {
        ...data,
    });
}
