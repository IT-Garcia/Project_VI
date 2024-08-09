#include "audioPlayer.h"
#include <SFML/Audio.hpp>
#include <iostream>

void playFloorAnnouncement(int floor) 
{
    sf::SoundBuffer buffer;
    sf::Sound sound;

    // Load the corresponding audio file based on the floor number
    std::string filename;
    switch (floor) 
    {
        case 1:
            filename = "Floor1.ogg";  // Replace with file paths
            break;
        case 2:
            filename = "Floor2.ogg";
            break;
        case 3:
            filename = "Floor3.ogg";
            break;
        default:
            std::cerr << "Invalid floor number!" << std::endl;
            return;
    }

    // Load the audio file into the buffer
    if (!buffer.loadFromFile(filename)) 
    {
        std::cerr << "Error loading sound file: " << filename << std::endl;
        return;
    }

    // Set the buffer to the sound object and play it
    sound.setBuffer(buffer);
    sound.play();

    // Wait until the sound is finished playing
    while (sound.getStatus() == sf::Sound::Playing) 
    {
        sf::sleep(sf::milliseconds(100));
    }
}
