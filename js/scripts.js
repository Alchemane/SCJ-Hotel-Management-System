document.addEventListener('DOMContentLoaded', function () {
    const hotelDropdown = document.getElementById('hotelID');

    if (hotelDropdown) {
        hotelDropdown.addEventListener('change', function () {
            const hotelID = this.value;

            fetch('../php/getAvailableRooms.php?hotelID=' + hotelID)
                .then(response => response.json())
                .then(data => {
                    const roomSelect = document.getElementById('roomID');
                    roomSelect.innerHTML = '<option value="" disabled selected>Select a room</option>'; // Reset dropdown

                    data.forEach(room => {
                        const option = document.createElement('option');
                        option.value = room.roomID;
                        option.textContent = 'Room ' + room.roomNumber;
                        roomSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error fetching rooms:', error));
        });
    } else {
        console.error("Element with ID 'hotelID' not found.");
    }
});