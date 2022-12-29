import math
import string
import sys

import numpy as np
from sympy import Matrix


def menu():
    while True:
        print("---- Hill Cipher ----\n")
        print("1) Enkripsi")
        print("2) Deskripsi")
        print("3) Keluar\n")
        try:
            choice = int(input("Pilih Menu : "))
            if 1 <= choice <= 3:
                return choice
            else:
                print("\nKamu harus milih 1 to 3\n")
        except ValueError:
            print("\nKamu harus milih 1 to 3\n")
        input("Tekan Enter untuk lanjutkan\n")


# Create two dictionaries, english huruf to numbers and numbers to english huruf, and returns them
def dapat_huruf():
    huruf = {}
    for char in string.ascii_uppercase:
        huruf[char] = string.ascii_uppercase.index(char)

    balik_huruf = {}
    for kunci, value in huruf.items():
        balik_huruf[value] = kunci

    return huruf, balik_huruf


# Get input from the user and checks if respects the huruf
def text_input(pesan, huruf):
    while True:
        text = input(pesan)
        text = text.upper()
        if all(kuncis in huruf for kuncis in text):
            return text
        else:
            print("\nHarus huruf ([A to Z] or [a to z]).")


# Check if the kunci is a square in length
def is_square(kunci):
    panjang_kunci = len(kunci)
    if 2 <= panjang_kunci == int(math.sqrt(panjang_kunci)) ** 2:
        return True
    else:
        return False


# Create the matrix k for the kunci
def kunci_matrix(kunci, huruf):
    k = list(kunci)
    m = int(math.sqrt(len(k)))
    for (i, char) in enumerate(k):
        k[i] = huruf[char]

    return np.reshape(k, (m, m))


# Create the matrix of m-grams of a text, if needed, complete the last m-gram with the last letter of the huruf
def text_matrix(text, m, huruf):
    matrix = list(text)
    sisa = len(text) % m
    for (i, char) in enumerate(matrix):
        matrix[i] = huruf[char]
    if sisa != 0:
        for i in range(m - sisa):
            matrix.append(25)

    return np.reshape(matrix, (int(len(matrix) / m), m)).transpose()


# enkripsi a pesan and returns the ciphertext matrix
def enkripsi(kunci, plaintext, huruf):
    m = kunci.shape[0]
    m_grams = plaintext.shape[1]

    # enkripsi the plaintext with the kunci provided k, calculate matrix c of ciphertext
    ciphertext = np.zeros((m, m_grams)).astype(int)
    for i in range(m_grams):
        ciphertext[:, i] = np.reshape(np.dot(kunci, plaintext[:, i]) % len(huruf), m)
    return ciphertext


# Transform a matrix to a text, according to the huruf
def matrix_text(matrix, order, huruf):
    if order == 't':
        text_array = np.ravel(matrix, order='F')
    else:
        text_array = np.ravel(matrix)
    text = ""
    for i in range(len(text_array)):
        text = text + huruf[text_array[i]]
    return text


# Check if the kunci is invertible and in that case returns the inverse of the matrix
def inverse(matrix, huruf):
    huruf_len = len(huruf)
    if math.gcd(int(round(np.linalg.det(matrix))), huruf_len) == 1:
        matrix = Matrix(matrix)
        return np.matrix(matrix.inv_mod(huruf_len))
    else:
        return None


# deskripsi a pesan and returns the plaintext matrix
def deskripsi(k_inverse, c, huruf):
    return enkripsi(k_inverse, c, huruf)


def main():
    while True:
        # Ask the user what function wants to run
        choice = menu()

        # Get two dictionaries, english huruf to numbers and numbers to english huruf
        huruf, balik_huruf = dapat_huruf()

        # Run the function selected by the user
        if choice == 1:
            # Asks the user the plaintext and the kunci for the enkripsiion and checks the input
            plaintext = text_input("\nMasukan kalimat enkripsi : ", huruf)
            kunci = text_input("Masukan kalimat enkripsi : ", huruf)

            if is_square(kunci):
                # Get the kunci matrix k
                k = kunci_matrix(kunci, huruf)
                print("\nkunci Matrix:\n", k)

                # Get the m-grams matrix p of the plaintext
                p = text_matrix(plaintext, k.shape[0], huruf)
                print("Plaintext :\n", p)
                
                # enkripsi the plaintext
                c = enkripsi(k, p, huruf)

                # Transform the ciphertext matrix to a text of the huruf
                ciphertext = matrix_text(c, "t", balik_huruf)

                print("\nEnkripsi\n")
                print("Hasil Ciphertext : ", ciphertext)
                print("Hasil Ciphertext Matrix :\n", c, "\n")
            else:
                print("\nThe length of the kunci must be a square and >= 2.\n")

        elif choice == 2:
            # Asks the user the ciphertext and the kunci for the enkripsiion and checks the input
            ciphertext = text_input("\nInsert the ciphertext to be deskripsied: ", huruf)
            kunci = text_input("Insert the kunci for deskripsiion: ", huruf)

            if is_square(kunci):
                # Get the kunci matrix k
                k = kunci_matrix(kunci, huruf)

                # Check if the kunci is invertible and in that case returns the inverse of the matrix
                k_inverse = inverse(k, huruf)

                if k_inverse is not None:
                    # Get the m-grams matrix c of the ciphertext
                    c = text_matrix(ciphertext, k_inverse.shape[0], huruf)

                    print("\nkunci Matrix:\n", k)
                    print("Ciphertext Matrix:\n", c)

                    # deskripsi the ciphertext
                    p = deskripsi(k_inverse, c, huruf)

                    # Transform the ciphertext matrix to a text of the huruf
                    plaintext = matrix_text(p, "t", balik_huruf)
                    
                    print("\Deskripsi\n")
                    print("Hasil Ciphertext : ", plaintext)
                    print("Hasil Ciphertext Matrix :\n", p, "\n")

                else:
                    print("\nThe matrix of the kunci provided is not invertible.\n")
            else:
                print("\nThe kunci must be a square and size >= 2.\n")

        
        elif choice == 3:
            sys.exit(0)
        input("Press Enter to continue.\n")


if __name__ == '__main__':
    main()